<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\LessonPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Illuminate\Support\Str;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Apply auth middleware to all methods except webhook handler
        $this->middleware('auth')->except(['handleNotification']);
        
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function createInvoiceMidtrans(Request $request)
    {
        // Validasi input
        $request->validate([
            'lesson_package_id' => 'required|exists:lesson_packages,lesson_package_id',
            'email' => 'required|email',
            'scheduled_start_date' => 'required|date|after_or_equal:today',
            'schedule_notes' => 'nullable|string|max:255',
            'payment_gateway' => 'required|in:xendit,midtrans',
        ]);

        try {
            $package = LessonPackage::findOrFail($request->lesson_package_id);
            $user = Auth::user();

            if (!$user) {
                Log::error('User not authenticated in createInvoiceMidtrans');
                throw new \Exception('User not authenticated');
            }

            if (!$package->lesson_package_price || $package->lesson_package_price <= 0) {
                throw new \Exception('Invalid lesson package price');
            }

            $externalId = (string) Str::uuid();

            // Simpan invoice ke database terlebih dahulu
            $params = [
                'external_id' => $externalId,
                'user_id' => $user->user_id,
                'lesson_package_id' => $package->lesson_package_id,
                'midtrans_order_id' => $externalId,
                'amount' => (int) $package->lesson_package_price,
                'payer_email' => $request->email,
                'status' => 'pending',
                'description' => 'Pembayaran untuk paket ' . $package->lesson_package_name,
                'payment_gateway' => $request->payment_gateway,
            ];

            $invoice = Invoice::create($params);

            // Siapkan data untuk Midtrans
            $transactionDetails = [
                'order_id' => $invoice->midtrans_order_id,
                'gross_amount' => (int) $package->lesson_package_price,
            ];

            $customer = [
                'email' => $invoice->payer_email,
                'first_name' => $user->first_name ?? '',
                'last_name' => $user->last_name ?? '',
            ];

            $createInvoice = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customer,
                'enabled_payments' => ['gopay', 'bank_transfer', 'credit_card', 'shopeepay'],
                'expiry' => [
                    'start_time' => now()->format('Y-m-d H:i:s O'),
                    'unit' => 'hours',
                    'duration' => 3,
                ],
            ];

            $snapToken = Snap::getSnapToken($createInvoice);
            
            // Update invoice dengan snap token dan URL
            $invoice->update([
                'invoice_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
            ]);

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'invoice_url' => $invoice->invoice_url,
                'invoice_id' => $invoice->invoice_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating Midtrans invoice: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        try {
            // Log semua data yang masuk untuk debug
            Log::info('Midtrans webhook called', [
                'method' => $request->method(),
                'content_type' => $request->header('content-type'),
                'user_agent' => $request->header('user-agent'),
                'has_data' => !empty($request->all()),
                'raw_input' => $request->getContent(),
                'parsed_data' => $request->all()
            ]);

            // Handle test notification dari Midtrans Dashboard (biasanya kosong atau minimal data)
            if (empty($request->all()) || $request->has('test') || !$request->has('order_id')) {
                Log::info('Handling test/ping notification from Midtrans');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Webhook endpoint is working'
                ], 200);
            }

            // Coba parse menggunakan Midtrans Notification class
            try {
                $notification = new Notification();
                
                $orderId = $notification->order_id;
                $transactionStatus = $notification->transaction_status;
                $paymentType = $notification->payment_type;
                $transactionId = $notification->transaction_id;
                $fraudStatus = $notification->fraud_status ?? null;
                
            } catch (\Exception $e) {
                Log::error('Error parsing Midtrans notification: ' . $e->getMessage());
                
                // Fallback: parse manual dari request
                $orderId = $request->input('order_id');
                $transactionStatus = $request->input('transaction_status');
                $paymentType = $request->input('payment_type');
                $transactionId = $request->input('transaction_id');
                $fraudStatus = $request->input('fraud_status');
                
                if (!$orderId || !$transactionStatus) {
                    Log::error('Invalid notification data - missing required fields');
                    return response()->json([
                        'status' => 'error', 
                        'message' => 'Invalid notification data'
                    ], 400);
                }
            }

            Log::info('Midtrans notification parsed successfully', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'transaction_id' => $transactionId,
                'fraud_status' => $fraudStatus,
            ]);

            // Cari invoice berdasarkan order_id
            $invoice = Invoice::where('midtrans_order_id', $orderId)->first();

            if (!$invoice) {
                Log::warning("Invoice not found for order_id: $orderId");
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Invoice not found'
                ], 404);
            }

            Log::info("Processing invoice: {$invoice->invoice_id}, current status: {$invoice->status}");

            // Update invoice dengan informasi dari Midtrans
            $invoice->midtrans_transaction_id = $transactionId;
            $invoice->midtrans_transaction_status = $transactionStatus;
            $invoice->midtrans_response = json_encode($request->all());
            
            if ($paymentType) {
                $invoice->payment_method = $paymentType;
            }

            // Tentukan status berdasarkan transaction_status
            if ($transactionStatus == 'settlement' && ($fraudStatus == 'accept' || $fraudStatus == null)) {
                $invoice->status = 'paid';
                Log::info("Invoice {$invoice->invoice_id} marked as PAID");
                
                // Proses financial log dan user package activation
                $this->processSuccessfulPayment($invoice);
                
            } elseif ($transactionStatus == 'pending') {
                $invoice->status = 'pending';
                Log::info("Invoice {$invoice->invoice_id} status: PENDING");
                
            } elseif ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $invoice->status = 'pending';
                    Log::info("Invoice {$invoice->invoice_id} status: PENDING (challenge)");
                } else if ($fraudStatus == 'accept' || $fraudStatus == null) {
                    $invoice->status = 'paid';
                    Log::info("Invoice {$invoice->invoice_id} marked as PAID (capture)");
                    $this->processSuccessfulPayment($invoice);
                }
                
            } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny', 'failure'])) {
                $invoice->status = 'failed';
                Log::info("Invoice {$invoice->invoice_id} status: FAILED");
            }

            // Simpan kode pembayaran jika ada
            $vaNumbers = $request->input('va_numbers');
            $paymentCode = $request->input('payment_code');
            
            if (!empty($vaNumbers) && is_array($vaNumbers) && isset($vaNumbers[0]['va_number'])) {
                $invoice->midtrans_payment_code = $vaNumbers[0]['va_number'];
            } elseif ($paymentCode) {
                $invoice->midtrans_payment_code = $paymentCode;
            }

            $invoice->save();
            
            Log::info("Invoice {$invoice->invoice_id} updated successfully with status: {$invoice->status}");
            
            return response()->json([
                'status' => 'success', 
                'message' => 'Notification processed successfully'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Error in webhook handler: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function paymentRedirect(Request $request)
    {
        $orderId = $request->order_id;
        $invoice = Invoice::where('midtrans_order_id', $orderId)->first();

        if (!$invoice) {
            return redirect()->route('home')->with('error', 'Invoice tidak ditemukan');
        }

        if ($invoice->status == 'paid') {
            return redirect()->route('success')->with('success', 'Pembayaran berhasil!');
        } else {
            return redirect()->route('home')->with('error', 'Pembayaran gagal atau masih tertunda');
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            
            Log::info("Checking payment status for order: $orderId");
            
            if (!$orderId) {
                return response()->json(['error' => 'Order ID required'], 400);
            }

            // Cek dari database dulu
            $invoice = Invoice::where('midtrans_order_id', $orderId)->first();
            
            if (!$invoice) {
                Log::warning("Invoice not found in database for order: $orderId");
                return response()->json(['error' => 'Invoice not found'], 404);
            }

            Log::info("Invoice found in database", [
                'invoice_id' => $invoice->invoice_id,
                'current_status' => $invoice->status,
                'midtrans_transaction_status' => $invoice->midtrans_transaction_status
            ]);

            // Jika sudah paid di database, return langsung
            if (in_array(strtolower($invoice->status), ['paid', 'settlement'])) {
                Log::info("Invoice already paid, returning success");
                return response()->json([
                    'status' => 'paid',
                    'message' => 'Payment successful',
                    'invoice' => $invoice
                ]);
            }

            // Jika belum, cek ke Midtrans API
            try {
                Log::info("Checking status from Midtrans API for order: $orderId");
                
                $status = \Midtrans\Transaction::status($orderId);
                
                // Convert to array untuk akses yang aman
                $statusData = json_decode(json_encode($status), true);
                
                Log::info('Midtrans API status result', [
                    'order_id' => $orderId,
                    'transaction_status' => $statusData['transaction_status'] ?? 'unknown',
                    'payment_type' => $statusData['payment_type'] ?? 'unknown',
                    'full_response' => $statusData
                ]);

                $transactionStatus = $statusData['transaction_status'] ?? '';
                $fraudStatus = $statusData['fraud_status'] ?? null;

                // Update invoice berdasarkan status dari Midtrans
                if ($transactionStatus == 'settlement' && ($fraudStatus == 'accept' || $fraudStatus == null)) {
                    Log::info("Payment confirmed as settlement, updating invoice to paid");
                    
                    $invoice->status = 'paid';
                    $invoice->midtrans_transaction_status = $transactionStatus;
                    $invoice->midtrans_transaction_id = $statusData['transaction_id'] ?? '';
                    $invoice->payment_method = $statusData['payment_type'] ?? '';
                    $invoice->save();

                    // Proses financial log dan user package activation
                    $this->processSuccessfulPayment($invoice);

                    return response()->json([
                        'status' => 'paid',
                        'message' => 'Payment successful',
                        'invoice' => $invoice
                    ]);
                    
                } elseif ($transactionStatus == 'capture') {
                    Log::info("Payment status is capture, checking fraud status");
                    
                    if ($fraudStatus == 'accept' || $fraudStatus == null) {
                        $invoice->status = 'paid';
                        $invoice->midtrans_transaction_status = $transactionStatus;
                        $invoice->midtrans_transaction_id = $statusData['transaction_id'] ?? '';
                        $invoice->payment_method = $statusData['payment_type'] ?? '';
                        $invoice->save();

                        $this->processSuccessfulPayment($invoice);

                        return response()->json([
                            'status' => 'paid',
                            'message' => 'Payment successful',
                            'invoice' => $invoice
                        ]);
                    } else {
                        $invoice->status = 'pending';
                        $invoice->midtrans_transaction_status = $transactionStatus;
                        $invoice->save();

                        return response()->json([
                            'status' => 'pending',
                            'message' => 'Payment needs review'
                        ]);
                    }
                    
                } elseif ($transactionStatus == 'pending') {
                    Log::info("Payment still pending");
                    
                    $invoice->status = 'pending';
                    $invoice->midtrans_transaction_status = $transactionStatus;
                    $invoice->save();

                    return response()->json([
                        'status' => 'pending',
                        'message' => 'Payment is pending'
                    ]);
                    
                } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny', 'failure'])) {
                    Log::info("Payment failed with status: $transactionStatus");
                    
                    $invoice->status = 'failed';
                    $invoice->midtrans_transaction_status = $transactionStatus;
                    $invoice->save();

                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Payment failed or expired'
                    ]);
                }

                Log::info("Unknown transaction status: $transactionStatus");
                return response()->json([
                    'status' => $transactionStatus,
                    'message' => 'Transaction status: ' . $transactionStatus
                ]);

            } catch (\Exception $e) {
                Log::error('Error checking Midtrans API: ' . $e->getMessage(), [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
                
                // Jika error API, return status dari database
                return response()->json([
                    'status' => $invoice->status,
                    'message' => 'Status from database: ' . $invoice->status,
                    'api_error' => true
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in checkPaymentStatus: ' . $e->getMessage(), [
                'order_id' => $request->input('order_id'),
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function processSuccessfulPayment($invoice)
    {
        // Catat ke financial log jika belum ada
        $existingLog = \App\Models\FinancialLog::where('invoice_id', $invoice->invoice_id)->first();
        if (!$existingLog) {
            \App\Models\FinancialLog::create([
                'invoice_id' => $invoice->invoice_id,
                'user_id' => $invoice->user_id,
                'amount' => $invoice->amount,
                'financial_type' => 'income',
                'payment_method' => $invoice->payment_method,
                'description' => 'Pembayaran untuk lesson package ' . ($invoice->lessonPackage->lesson_package_name ?? ''),
                'transaction_date' => now(),
            ]);
            Log::info("Financial log created for invoice ID: {$invoice->invoice_id}");
        }

        // Aktivasi user lesson package
        $user = $invoice->user;
        $package = $invoice->lessonPackage;

        if ($user && $package) {
            $existingUserPackage = \App\Models\UserLessonPackage::where('invoice_id', $invoice->invoice_id)->first();
            
            if (!$existingUserPackage) {
                $now = \Carbon\Carbon::now();
                $lastActive = $user->userLessonPackages()
                    ->where('status', 'active')
                    ->where('end_date', '>', $now)
                    ->orderByDesc('end_date')
                    ->first();

                $startDate = $lastActive ? \Carbon\Carbon::parse($lastActive->end_date) : $now;
                $endDate = $package->getEndDate($startDate);

                \App\Models\UserLessonPackage::create([
                    'user_id' => $user->user_id,
                    'lesson_package_id' => $package->lesson_package_id,
                    'invoice_id' => $invoice->invoice_id,
                    'purchased_at' => now(),
                    'scheduled_start_date' => $startDate,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                ]);

                Log::info("User premium status activated for user ID: {$user->user_id}, package ID: {$package->lesson_package_id}");
            }
        }
    }
}
