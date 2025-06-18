<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_lesson_packages', function (Blueprint $table) {
            $table->id('user_lesson_package_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lesson_package_id');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->timestamp('purchased_at')->useCurrent(); // Waktu pembelian/checkout
            $table->timestamp('scheduled_start_date')->nullable(); // Tanggal yang dipilih user untuk mulai paket
            $table->timestamp('start_date')->nullable(); // Tanggal aktual mulainya paket (bisa sama dengan scheduled_start_date)
            $table->timestamp('end_date')->nullable(); // Tanggal berakhirnya paket
            $table->enum('status', ['active', 'expired', 'pending', 'scheduled'])->default('scheduled'); // Tambah status 'scheduled'
            $table->text('notes')->nullable(); // Untuk catatan tambahan terkait penjadwalan
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('lesson_package_id')->references('lesson_package_id')->on('lesson_packages')->onDelete('cascade');
            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_lesson_packages');
    }
};
