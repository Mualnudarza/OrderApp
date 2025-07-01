<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_orders_table.php

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemesan'); // Nama pelanggan yang memesan
            $table->string('meja_nomor')->nullable(); // Nomor meja, opsional
            $table->decimal('total_harga', 10, 2); // Total harga pesanan
            $table->string('status')->default('pending'); // Status pesanan (misal: pending, completed, cancelled)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

