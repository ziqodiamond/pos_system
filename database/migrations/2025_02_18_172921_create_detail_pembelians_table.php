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
            $table->foreignUuid('barang_id')->constrained('barangs')->restrictOnDelete();
            $table->unsignedInteger('qty_user'); //kuantitaas yg di input user
            $table->unsignedInteger('qty_base');  //kuantitas yg udh dikonversi
            $table->foreignUuid('satuan_id')->constrained('satuans')->restrictOnDelete();
            $table->foreignUuid('satuan_dasar_id')->constrained('satuans')->restrictOnDelete();
            $table->unsignedBigInteger('harga_satuan')->default(0);
            $table->unsignedBigInteger('harga_diskon')->default(0);
            $table->unsignedBigInteger('harga_pokok')->default(0); // hharga_diskon + other_cost + pajak_value
            $table->unsignedBigInteger('other_cost')->default(0);
            $table->unsignedBigInteger('diskon_value')->default(0); //diskon satuan
            $table->unsignedBigInteger('pajak_value'); //pajak satuan
            $table->foreignUuid('pajak_id')->nullable()->constrained('pajaks')->restrictOnDelete();
            $table->unsignedBigInteger('subtotal'); // tanpa pajak tanpa diskon (harga_diskon * kuantitas)
            $table->unsignedBigInteger('total'); // dengan pajak, diskon, other cost (harga_pokok * kuantitas)
            $table->unsignedInteger('stok');
            $table->enum('status', ['processing', 'received', 'completed', 'cenceled'])->default('processing');
            $table->timestamps();
            $table->softDeletes();
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
