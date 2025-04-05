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
            $table->foreignUuid('kasir_id')->constrained('users');
            $table->foreignUuid('customer_id')->constrained('customers');
            $table->unsignedBigInteger('subtotal'); //tanpa diskon include pajak
            $table->unsignedBigInteger('total_diskon'); //total diskon barang
            $table->unsignedBigInteger('total_pajak'); //total pajak barang
            $table->unsignedBigInteger('grand_total'); //subtotal - total_dikon
            $table->unsignedBigInteger('total_bayar');
            $table->unsignedBigInteger('kembalian');
            $table->enum('metode_pembayaran', ['tunai', 'debit', 'e-wallet']);
            $table->timestamps();
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
