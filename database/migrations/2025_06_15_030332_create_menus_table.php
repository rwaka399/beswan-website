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
        Schema::create('menus', function (Blueprint $table) {
            $table->id('menu_id');
            $table->string('menu_name', 100)->nullable();
            $table->string('menu_type', 100)->nullable();
            $table->string('menu_icon', 100)->nullable();
            $table->string('menu_link', 100)->nullable();
            $table->integer('menu_urutan')->nullable();
            $table->bigInteger('menu_parent')->nullable();
            $table->string('menu_slug', 100);
            // $table->enum('status', ['ENABLE', 'DISABLE'])->nullable()->default('ENABLE');
            $table->timestamps();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();

            $table->foreign('created_by')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
