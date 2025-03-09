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
        Schema::create('detail_retur_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('retur_penjualan_id')->constrained('retur_penjualans')->cascadeOnDelete();
            $table->foreignUuid('barang_id')->constrained('barangs');
            $table->string('nama_barang');
            $table->unsignedInteger('kuantitas');
            $table->foreignUuid('satuan_id')->constrained('satuans')->restrictOnDelete();
            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedBigInteger('subtotal');
            $table->enum('kondisi_barang', ['rusak', 'baik']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_retur_penjualans');
    }
};
