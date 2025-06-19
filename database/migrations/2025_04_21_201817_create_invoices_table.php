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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->string('external_id')->unique();
            $table->string('invoice_url')->nullable();
            $table->string('xendit_invoice_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('lesson_package_id');

            $table->decimal('amount', 15, 2);
            $table->string('payer_email');
            $table->string('status')->default('pending');
            $table->text('description')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->date('scheduled_start_date')->nullable();
            $table->text('schedule_notes')->nullable();

            $table->string('payment_gateway')->default('xendit');

            
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('lesson_package_id')->references('lesson_package_id')->on('lesson_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
