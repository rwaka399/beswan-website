<?php

namespace App\Http\Controllers;

use App\Models\FinancialLog;
use App\Models\Invoice;
use App\Models\User;
use App\Models\LessonPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FinancialLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Tampilkan daftar semua log keuangan
     */
    public function index(Request $request)
    {
        $query = FinancialLog::with(['user', 'invoice.lessonPackage']);

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Filter berdasarkan jenis transaksi
        if ($request->filled('financial_type')) {
            $query->where('financial_type', $request->financial_type);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->orderBy('transaction_date', 'desc')
            ->paginate(20);

        // Data untuk filter dropdown
        $users = User::select('user_id', 'name')->get();
        $paymentMethods = FinancialLog::distinct()->pluck('payment_method')->filter();

        // Statistik ringkasan
        $totalIncome = FinancialLog::where('financial_type', 'income')->sum('amount');
        $totalExpense = FinancialLog::where('financial_type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return view('financial.index', compact(
            'logs',
            'users',
            'paymentMethods',
            'totalIncome',
            'totalExpense',
            'netProfit'
        ));
    }

    /**
     * Tampilkan form untuk membuat log keuangan baru
     */
    public function create()
    {
        $users = User::select('user_id', 'name')->get();
        $invoices = Invoice::with('lessonPackage')->select('invoice_id', 'external_id', 'lesson_package_id')->get();

        return view('financial.create', compact('users', 'invoices'));
    }

    /**
     * Simpan log keuangan baru
     */
    public function store(Request $request)
    {
        // Validasi input - dengan null safety untuk role
        $user = Auth::user();
        $userAdmin = ($user->userRoles() === 'Admin') ? Auth::id() : null;

        $request->merge([
            'user_id' => Auth::id(), // Set user_id to authenticated user
            'created_by' => $userAdmin,
            'updated_by' => Auth::id(),
        ]);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'financial_type' => 'required|in:income,expense',
            'payment_method' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
            'invoice_id' => 'nullable|exists:invoices,invoice_id',
        ], [
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.numeric' => 'Jumlah harus berupa angka.',
            'amount.min' => 'Jumlah tidak boleh negatif.',
            'financial_type.required' => 'Jenis transaksi harus dipilih.',
            'financial_type.in' => 'Jenis transaksi harus income atau expense.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',
        ]);

        try {
            $financialLog = FinancialLog::create([
                'invoice_id' => $request->invoice_id ?: null,
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'financial_type' => $request->financial_type,
                'payment_method' => $request->payment_method ?: null,
                'description' => $request->description ?: '-',
                'transaction_date' => $request->transaction_date,
                'created_by' => $request->created_by ?: null,
                'updated_by' => $request->updated_by ?: null,
            ]);

            return redirect()->route('financial-index')
                ->with('success', 'Log keuangan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail log keuangan
     */
    public function show($id)
    {
        $financialLog = FinancialLog::with(['user', 'invoice.lessonPackage'])
            ->findOrFail($id);

        return view('financial.show', compact('financialLog'));
    }

    /**
     * Tampilkan form edit log keuangan
     */
    public function edit($id)
    {
        $financialLog = FinancialLog::findOrFail($id);
        $users = User::select('user_id', 'name')->get();
        $invoices = Invoice::with('lessonPackage')->select('invoice_id', 'external_id', 'lesson_package_id')->get();

        return view('financial.edit', compact('financialLog', 'users', 'invoices'));
    }

    /**
     * Update log keuangan
     */
    public function update(Request $request, $id)
    {
        $financialLog = FinancialLog::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'financial_type' => 'required|in:income,expense',
            'payment_method' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
            'invoice_id' => 'nullable|exists:invoices,invoice_id',
        ], [
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.numeric' => 'Jumlah harus berupa angka.',
            'description.required' => 'Deskripsi wajib diisi.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
        ]);

        // Null safety untuk role check
        $user = Auth::user();
        $userAdmin = ($user && $user->role && $user->role->role_name === 'Admin') ? Auth::id() : $financialLog->created_by;

        $request->merge([
            'created_by' => $userAdmin,
            'updated_by' => Auth::id(),
        ]);

        try {
            $financialLog->update([
                'invoice_id' => $request->invoice_id ?: null,
                'amount' => $request->amount,
                'financial_type' => $request->financial_type,
                'payment_method' => $request->payment_method ?: null,
                'description' => $request->description ?: '-',
                'transaction_date' => $request->transaction_date,
                'created_by' => $request->created_by ?: null,
                'updated_by' => $request->updated_by ?: null,
            ]);

            return redirect()->route('financial-index')
                ->with('success', 'Log keuangan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus log keuangan
     */
    public function destroy($id)
    {
        try {
            $financialLog = FinancialLog::findOrFail($id);
            $financialLog->delete();

            return redirect()->route('financial-index')
                ->with('success', 'Log keuangan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Laporan keuangan dengan grafik dan statistik
     */
    public function report(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Data untuk grafik pendapatan vs pengeluaran per bulan
        $monthlyData = FinancialLog::select(
            DB::raw('EXTRACT(YEAR FROM transaction_date) as year'),
            DB::raw('EXTRACT(MONTH FROM transaction_date) as month'),
            DB::raw('SUM(CASE WHEN financial_type = \'income\' THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN financial_type = \'expense\' THEN amount ELSE 0 END) as expense'),
            DB::raw('COUNT(*) as transactions')
        )
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                $monthNames = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ];
                $item->month_name = $monthNames[$item->month] . ' ' . $item->year;
                return $item;
            });

        // Top 5 paket pembelajaran terlaris
        $topPackages = FinancialLog::select('lesson_packages.lesson_package_name', DB::raw('COUNT(*) as total_sales'), DB::raw('SUM(financial_logs.amount) as total_revenue'))
            ->join('invoices', 'financial_logs.invoice_id', '=', 'invoices.invoice_id')
            ->join('lesson_packages', 'invoices.lesson_package_id', '=', 'lesson_packages.lesson_package_id')
            ->where('financial_logs.financial_type', 'income')
            ->whereBetween('financial_logs.transaction_date', [$startDate, $endDate])
            ->groupBy('lesson_packages.lesson_package_id', 'lesson_packages.lesson_package_name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        // Statistik pembayaran per metode
        $paymentMethodStats = FinancialLog::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->where('financial_type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->orderBy('total', 'desc')
            ->get();

        // Ringkasan total
        $totalIncome = FinancialLog::where('financial_type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpense = FinancialLog::where('financial_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $netProfit = $totalIncome - $totalExpense;

        // Total transaksi
        $totalTransactions = FinancialLog::whereBetween('transaction_date', [$startDate, $endDate])->count();

        return view('financial.report', compact(
            'monthlyData',
            'topPackages',
            'paymentMethodStats',
            'totalIncome',
            'totalExpense',
            'netProfit',
            'totalTransactions',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export laporan ke Excel/CSV
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $logs = FinancialLog::with(['user', 'invoice.lessonPackage'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'desc')
            ->get();

        if ($format === 'csv') {
            return $this->exportToCsv($logs, $startDate, $endDate);
        }

        return back()->with('error', 'Format export tidak didukung.');
    }

    /**
     * Export data ke CSV
     */
    private function exportToCsv($logs, $startDate, $endDate)
    {
        $filename = "laporan_keuangan_{$startDate}_to_{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // BOM untuk Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header CSV
            fputcsv($file, [
                'Tanggal',
                'Nama User',
                'Jenis Transaksi',
                'Jumlah',
                'Metode Pembayaran',
                'Paket Pembelajaran',
                'Deskripsi'
            ]);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->transaction_date->format('d/m/Y H:i'),
                    $log->user->name ?? 'N/A',
                    ucfirst($log->financial_type),
                    number_format($log->amount, 0, ',', '.'),
                    $log->payment_method ?? 'N/A',
                    $log->invoice->lessonPackage->lesson_package_name ?? 'N/A',
                    $log->description
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Dashboard keuangan untuk user yang login
     */
    public function dashboard(Request $request)
    {
        $userId = Auth::id();

        $query = FinancialLog::where('financial_logs.user_id', $userId);

        // Handle period filters
        switch ($request->get('period')) {
            case 'today':
                $query->whereDate('transaction_date', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'year':
                $query->whereBetween('transaction_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                break;
        }

        // Handle specific month filter
        if ($request->filled('month')) {
            $month = $request->get('month');
            $year = Carbon::now()->year; // Use current year
            $query->whereYear('transaction_date', $year)
                  ->whereMonth('transaction_date', $month);
        }

        // Recent transactions for the user
        $recentTransactions = $query->with(['invoice.lessonPackage'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);

        // My total income (apply same filters)
        $incomeQuery = FinancialLog::where('user_id', $userId)
            ->where('financial_type', 'income');
        
        $expenseQuery = FinancialLog::where('user_id', $userId)
            ->where('financial_type', 'expense');

        $transactionQuery = FinancialLog::where('user_id', $userId);

        // Apply the same date filters to summary statistics
        switch ($request->get('period')) {
            case 'today':
                $incomeQuery->whereDate('transaction_date', Carbon::today());
                $expenseQuery->whereDate('transaction_date', Carbon::today());
                $transactionQuery->whereDate('transaction_date', Carbon::today());
                break;
            case 'week':
                $incomeQuery->whereBetween('transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $expenseQuery->whereBetween('transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $transactionQuery->whereBetween('transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'year':
                $incomeQuery->whereBetween('transaction_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                $expenseQuery->whereBetween('transaction_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                $transactionQuery->whereBetween('transaction_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                break;
        }

        // Apply month filter to summary statistics
        if ($request->filled('month')) {
            $month = $request->get('month');
            $year = Carbon::now()->year;
            $incomeQuery->whereYear('transaction_date', $year)->whereMonth('transaction_date', $month);
            $expenseQuery->whereYear('transaction_date', $year)->whereMonth('transaction_date', $month);
            $transactionQuery->whereYear('transaction_date', $year)->whereMonth('transaction_date', $month);
        }

        $myTotalIncome = $incomeQuery->sum('amount');
        $myTotalExpense = $expenseQuery->sum('amount');
        $myTotalTransactions = $transactionQuery->count();

        // Monthly data for user (last 6 months)
        $myMonthlyData = FinancialLog::select(
            DB::raw('EXTRACT(YEAR FROM transaction_date) as year'),
            DB::raw('EXTRACT(MONTH FROM transaction_date) as month'),
            DB::raw('SUM(CASE WHEN financial_type = \'income\' THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN financial_type = \'expense\' THEN amount ELSE 0 END) as expense'),
            DB::raw('COUNT(*) as transactions')
        )
            ->where('user_id', $userId)
            ->where('transaction_date', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($item) {
                $monthNames = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ];
                $item->month_name = $monthNames[$item->month] . ' ' . $item->year;
                return $item;
            });

        return view('financial.dashboard', compact(
            'recentTransactions',
            'myTotalIncome',
            'myTotalExpense',
            'myTotalTransactions',
            'myMonthlyData'
        ));
    }
}
