<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FinancialLog;
use App\Models\Invoice;

echo "=== FINANCIAL LOGS CHECK ===\n";
echo "Total Financial Logs: " . FinancialLog::count() . "\n\n";

$logs = FinancialLog::latest()->take(3)->get();
foreach ($logs as $log) {
    echo "Invoice ID: {$log->invoice_id}\n";
    echo "Amount: {$log->amount}\n";
    echo "Type: {$log->financial_type}\n";
    echo "Date: {$log->transaction_date}\n";
    echo "---\n";
}

echo "\n=== PAID INVOICES ===\n";
$paidInvoices = Invoice::where('status', 'PAID')->get(['external_id', 'status', 'amount']);
foreach ($paidInvoices as $invoice) {
    echo "External ID: {$invoice->external_id} - Status: {$invoice->status} - Amount: {$invoice->amount}\n";
}
