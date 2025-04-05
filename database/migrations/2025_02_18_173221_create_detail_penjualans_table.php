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
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('penjualan_id')->constrained('penjualans')->cascadeOnDelete();
            $table->foreignUuid('barang_id')->constrained('barangs')->restrictOnDelete();
            $table->string('nama_barang');
            $table->unsignedBigInteger('harga_satuan'); //sudah termasuk pajak
            $table->unsignedBigInteger('harga_diskon'); //sudah termasuk pajak
            $table->unsignedBigInteger('pajak_value'); //pajak satuan
            $table->decimal('diskon_value', 5, 2);
            $table->unsignedTinyInteger('diskon_nominal'); //diskon satuan
            $table->unsignedInteger('kuantitas');
            $table->foreignUuid('satuan_id')->constrained('satuans')->restrictOnDelete();
            $table->unsignedBigInteger('total_diskon'); //diskon satuan * kuantitas
            $table->unsignedBigInteger('pajak'); //pajak satuan * kuantitas
            $table->unsignedBigInteger('subtotal'); // // subtotal tanpa diskon (harga_satuan * kuantitas)
            $table->unsignedBigInteger('total'); // total dengan diskon (harga_satuan * kuantitas) - diskon
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
