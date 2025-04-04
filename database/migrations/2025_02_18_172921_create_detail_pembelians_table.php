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
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pembelian_id')->constrained('pembelians')->cascadeOnDelete();
            $table->foreignUuid('barang_id')->constrained('barangs');
            $table->unsignedInteger('kuantitas');
            $table->foreignUuid('satuan_id')->constrained('satuans')->restrictOnDelete();
            $table->foreignUuid('satuan_dasar_id')->constrained('satuans')->restrictOnDelete();
            $table->unsignedBigInteger('harga_satuan')->default(0);
            $table->unsignedBigInteger('harga_diskon')->default(0);
            $table->unsignedBigInteger('other_cost')->default(0);
            $table->unsignedBigInteger('diskon_value')->default(0);
            $table->unsignedBigInteger('pajak_value');
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('total');
            $table->unsignedInteger('stok');
            $table->enum('status', ['processing', 'received', 'completed'])->default('processing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelians');
    }
};
