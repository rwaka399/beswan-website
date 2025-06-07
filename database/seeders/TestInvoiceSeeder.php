<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\User;
use App\Models\LessonPackage;
use Carbon\Carbon;

class TestInvoiceSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada user dan lesson package
        $user = User::first();
        $package = LessonPackage::first();

        if (!$user || !$package) {
            $this->command->error('User atau LessonPackage tidak ditemukan. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        // Buat test invoice
        Invoice::create([
            'external_id' => 'test-external-id-123',
            'xendit_invoice_id' => '60b9c123def456789abc0123',
            'user_id' => $user->user_id,
            'lesson_package_id' => $package->lesson_package_id,
            'amount' => 100000,
            'payer_email' => 'test@example.com',
            'description' => 'Test invoice for webhook testing',
            'status' => 'PENDING',
            'payment_method' => 'BCA',
            'invoice_url' => 'https://checkout.xendit.co/web/test123',
            'expires_at' => Carbon::now()->addHours(24),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->command->info('Test invoice created successfully with external_id: test-external-id-123');
    }
}
