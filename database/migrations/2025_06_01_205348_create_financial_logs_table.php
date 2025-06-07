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
        Schema::create('financial_logs', function (Blueprint $table) {
            $table->id('financial_log_id');
            $table->unsignedBigInteger('invoice_id')->nullable()->unique();
            $table->unsignedBigInteger('user_id');
            
            $table->decimal('amount', 15, 2);
            $table->string('financial_type');
            $table->string('payment_method')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('transaction_date');

            $table->timestamps();

            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_logs');
    }
};
