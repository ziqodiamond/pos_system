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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_ref');
            $table->foreignUuid('kasir_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('customer_id')->nullable()->constrained('customers')->restrictOnDelete();
            $table->unsignedBigInteger('subtotal'); //tanpa diskon include pajak
            $table->unsignedBigInteger('total_diskon'); //total diskon barang
            $table->unsignedBigInteger('total_pajak'); //total pajak barang
            $table->unsignedBigInteger('dpp'); //total diskon barang
            $table->unsignedBigInteger('grand_total'); //subtotal - total_dikon
            $table->unsignedBigInteger('total_bayar');
            $table->unsignedBigInteger('kembalian');
            $table->string('metode_pembayaran');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
