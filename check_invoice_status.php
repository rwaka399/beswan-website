<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Invoice;

echo "=== INVOICE STATUS CHECK ===\n";

$invoices = Invoice::latest()->take(5)->get(['external_id', 'status', 'updated_at']);

foreach ($invoices as $invoice) {
    echo "External ID: {$invoice->external_id}\n";
    echo "Status: {$invoice->status}\n";
    echo "Updated At: {$invoice->updated_at}\n";
    echo "---\n";
}

echo "Total invoices: " . Invoice::count() . "\n";
