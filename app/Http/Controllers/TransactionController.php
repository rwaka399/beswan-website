<?php

namespace App\Http\Controllers;

use App\Models\FinancialLog;
use App\Models\LessonPackage;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\UserLessonPackage;
use Carbon\Carbon;
// use App\Models\LogKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\InvoiceNotification;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\XenditSdkException;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('handleWebhook');
    }

    private function initializeXendit()
    {
        $apiKey = config('services.xendit.secret_key');

        if (!$apiKey) {
            throw new \Exception('Xendit API key is not configured. Please set XENDIT_SECRET_KEY in your .env file.');
        }

        Configuration::setXenditKey($apiKey);
        return new InvoiceApi();
    }

    // public function listPackages()
    // {
    //     return view('dashboard', [
    //         'lesson_packages' => LessonPackage::all(),
    //     ]);
    // }

    public function showCheckout($lessonPackageId)
    {
        $package = LessonPackage::findOrFail($lessonPackageId);
        return view('transaction.checkout', compact('package'));
    }

    public function createInvoice(Request $request)
    {
        $allowedPaymentMethods = [
            'MANDIRI',
            'BCA',
            'BRI',
            'BNI',
            'OVO',
            'DANA',
            'GOPAY',
            'SHOPEEPAY',
            'QRIS'
        ];

        $request->validate([
            'lesson_package_id' => 'required|exists:lesson_packages,lesson_package_id',
            'email' => 'required|email',
            'payment_method' => 'required|in:' . implode(',', $allowedPaymentMethods),
        ]);

        try {
            $apiKey = config('services.xendit.secret_key');
            Log::info('Xendit API Key configured: ' . (empty($apiKey) ? 'NO' : 'YES'));
            Log::info('API Key length: ' . strlen($apiKey ?? ''));

            if (empty($apiKey)) {
                throw new \Exception('Xendit API key is not properly configured');
            }

            $package = LessonPackage::findOrFail($request->lesson_package_id);
            $user = Auth::user();

            if (!$user) {
                Log::error('User not authenticated in createInvoice');
                throw new \Exception('User not authenticated');
            }

            if (!$package->lesson_package_price || $package->lesson_package_price <= 0) {
                throw new \Exception('Invalid lesson package price');
            }

            $externalId = (string) Str::uuid();
            Log::info('External ID generated: ' . $externalId);

            $params = [
                'external_id' => $externalId,
                'amount' => (int) $package->lesson_package_price,
                'payer_email' => $request->email,
                'description' => 'Pembayaran untuk paket ' . $package->lesson_package_name,
                'success_redirect_url' => url('/transaction/success'),
                'failure_redirect_url' => url('/transaction/failed'),
                'currency' => 'IDR',
                'payment_methods' => [$request->payment_method],
            ];

            Log::info('Params prepared for invoice: ' . json_encode($params));

            $createInvoiceRequest = new CreateInvoiceRequest($params);

            Log::info('Sending invoice request to Xendit');
            $invoiceApi = $this->initializeXendit();
            $result = $invoiceApi->createInvoice($createInvoiceRequest);

            Log::info('Invoice created successfully: ' . json_encode($result));

            $invoiceData = [
                'external_id' => $externalId,
                'xendit_invoice_id' => $result->getId(),
                'user_id' => $user->user_id,
                'lesson_package_id' => $package->lesson_package_id,
                'amount' => $package->lesson_package_price,
                'payer_email' => $request->email,
                'description' => $params['description'],
                'status' => $result->getStatus(),
                'payment_method' => $request->payment_method,
                'invoice_url' => $result->getInvoiceUrl(),
                'expires_at' => Carbon::parse($result->getExpiryDate()),
            ];

            Log::info('Saving invoice data: ' . json_encode($invoiceData));
            $invoice = Invoice::create($invoiceData);

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'invoice_url' => $result->getInvoiceUrl(),
                'data' => [
                    'invoice_id' => $invoice->invoice_id,
                    'external_id' => $externalId,
                    'amount' => $package->lesson_package_price,
                    'status' => $invoice->status,
                    'expires_at' => $invoice->expires_at->toISOString(),
                ]
            ], 201);
        } catch (XenditSdkException $e) {
            Log::error('Xendit SDK error: ' . $e->getMessage(), ['error' => $e->getFullError()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getFullError(),
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Error creating invoice: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Error creating invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        try {
            Log::info('Xendit Webhook received: ' . json_encode($request->all(), JSON_PRETTY_PRINT));

            // Verifikasi Signature jika disetting (skip untuk testing di development)
            $xenditWebhookSecret = config('services.xendit.webhook_secret');
            if ($xenditWebhookSecret && app()->environment('production')) {
                $signature = $request->header('x-xendit-signature');
                $expectedSignature = hash_hmac('sha256', $request->getContent(), $xenditWebhookSecret);

                if (!hash_equals($expectedSignature, $signature)) {
                    Log::error('Invalid webhook signature', [
                        'received' => $signature,
                        'expected' => $expectedSignature,
                    ]);
                    return response()->json(['message' => 'Invalid webhook signature'], 403);
                }
            } else {
                Log::info('Webhook signature verification skipped (development mode)');
            }

            $data = $request->all();
            $xenditInvoiceId = $data['id'] ?? null;
            $externalId = $data['external_id'] ?? null;
            $status = isset($data['status']) ? strtoupper($data['status']) : null;
            $paymentMethod = $data['payment_method'] ?? null;
            $paidAt = isset($data['paid_at']) ? Carbon::parse($data['paid_at']) : now();

            if (!$xenditInvoiceId || !$externalId || !$status) {
                Log::error('Missing required webhook data', ['data' => $data]);
                return response()->json(['message' => 'Missing required data'], 400);
            }

            // Validasi status yang valid
            $validStatuses = ['PENDING', 'PAID', 'EXPIRED', 'VOIDED'];
            if (!in_array($status, $validStatuses)) {
                Log::error('Invalid status received: ' . $status, ['data' => $data]);
                return response()->json(['message' => 'Invalid status'], 400);
            }

            $invoice = \App\Models\Invoice::where('external_id', $externalId)->first();

            if (!$invoice) {
                Log::warning("Invoice not found for external_id: $externalId");
                return response()->json(['message' => 'Invoice not found'], 404);
            }

            // Cegah duplikasi
            if (strtoupper($invoice->status) === 'PAID') {
                return response()->json(['message' => 'Invoice already settled'], 200);
            }

            // Update invoice
            $invoice->update([
                'status' => $status,
                'payment_method' => $paymentMethod ?? $invoice->payment_method,
                'xendit_invoice_id' => $xenditInvoiceId,
            ]);

            Log::info("Invoice updated: ID {$invoice->invoice_id}, Status: $status");

            // Catat ke financial log jika PAID
            if ($status === 'PAID') {
                $existingLog = \App\Models\FinancialLog::where('invoice_id', $invoice->invoice_id)->first();
                if (!$existingLog) {
                    \App\Models\FinancialLog::create([
                        'invoice_id' => $invoice->invoice_id,
                        'user_id' => $invoice->user_id,
                        'amount' => $invoice->amount,
                        'financial_type' => 'income',
                        'payment_method' => $paymentMethod ?? $invoice->payment_method,
                        'description' => 'Pembayaran untuk lesson package ' . ($invoice->lessonPackage->lesson_package_name ?? ''),
                        'transaction_date' => $paidAt,
                    ]);
                    Log::info("Financial log created for invoice ID: {$invoice->invoice_id}");
                }

                // Berikan status premium kepada user
                $user = $invoice->user;
                $package = $invoice->lessonPackage;

                if ($user && $package) {
                    // Cek apakah sudah ada record untuk invoice ini
                    $existingUserPackage = UserLessonPackage::where('invoice_id', $invoice->invoice_id)->first();
                    
                    if (!$existingUserPackage) {
                        // Tentukan tanggal mulai premium
                        $now = Carbon::now();

                        // Jika user masih punya premium aktif, perpanjang dari end_date terakhir
                        $lastActive = $user->userLessonPackages()
                            ->where('status', 'active')
                            ->where('end_date', '>', $now)
                            ->orderByDesc('end_date')
                            ->first();

                        $startDate = $lastActive ? Carbon::parse($lastActive->end_date) : $now;
                        $endDate = $package->getEndDate($startDate);

                        // Buat record baru di user_lesson_packages
                        UserLessonPackage::create([
                            'user_id' => $user->user_id,
                            'lesson_package_id' => $package->lesson_package_id,
                            'invoice_id' => $invoice->invoice_id,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'status' => 'active',
                        ]);

                        Log::info("User premium status activated for user ID: {$user->user_id}, package ID: {$package->lesson_package_id}");
                    }
                }
            }

            return response()->json([
                'message' => 'Webhook processed successfully',
                'status' => $status
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error handling webhook: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request' => collect($request->all())->except(['payer_email'])->toArray(),
            ]);

            return response()->json([
                'message' => 'Error processing webhook',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function success(Request $request)
    {
        $invoice = Invoice::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();
        return view('transaction.success', compact('invoice'));
    }

    public function failed()
    {
        return view('failed');
    }

    // public function financialReport()
    // {
    //     $logs = LogKeuangan::with(['user', 'lessonPackage'])->orderBy('transaction_date', 'desc')->get();
    //     return view('report', compact('logs'));
    // }

    public function invoiceHistory()
    {
        $invoices = Invoice::where('user_id', Auth::id())->with('lessonPackage')->get();
        return view('profile.history', compact('invoices'));
    }
}
