<?php

require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Invoice;

// Xendit configuration
\Xendit\Configuration::setXenditKey($_ENV['XENDIT_SECRET_KEY']);

echo "=== CHECKING PENDING INVOICES WITH XENDIT ===\n\n";

// Get all pending invoices
$pendingInvoices = Invoice::where('status', 'PENDING')->get();

foreach ($pendingInvoices as $invoice) {
    echo "Checking Invoice: {$invoice->external_id}\n";
      try {
        // Check status with Xendit API
        $apiInstance = new \Xendit\Invoice\InvoiceApi();
        $xenditInvoice = $apiInstance->getInvoiceById($invoice->external_id);
          echo "Local Status: {$invoice->status}\n";
        echo "Xendit Status: {$xenditInvoice->getStatus()}\n";
        echo "Amount: Rp " . number_format($invoice->amount, 0, ',', '.') . "\n";
        echo "Created: {$invoice->created_at}\n";
        
        // If paid in Xendit but not in our database
        if ($xenditInvoice->getStatus() === 'PAID' && $invoice->status === 'PENDING') {
            echo "🔥 FOUND MISMATCH! Invoice is PAID in Xendit but PENDING locally\n";
            echo "Updating local status...\n";
            
            // Update local status
            $invoice->update(['status' => 'PAID']);
            
            // Create financial log
            \App\Models\FinancialLog::create([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->amount,
                'type' => 'income',
                'date' => now(),
            ]);
            
            echo "✅ Status updated to PAID and financial log created\n";
        } else {
            echo "✅ Status is consistent\n";
        }
        
        echo "---\n\n";
        
    } catch (Exception $e) {
        echo "❌ Error checking with Xendit: " . $e->getMessage() . "\n";
        echo "---\n\n";
    }
    
    // Add delay to avoid rate limiting
    sleep(1);
}

echo "=== CHECK COMPLETE ===\n";
