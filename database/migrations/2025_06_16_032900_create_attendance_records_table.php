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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id'); // Reference ke attendance
            $table->unsignedBigInteger('user_id'); // ID Guru
            $table->time('check_in_time')->nullable(); // Waktu guru absen
            $table->enum('status', ['present', 'absent', 'late'])->default('absent'); // Status: hadir, tidak hadir, terlambat
            $table->text('notes')->nullable(); // Catatan
            $table->timestamps();

            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users');
            
            // Unique constraint untuk mencegah guru absen dua kali di hari yang sama
            $table->unique(['attendance_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
