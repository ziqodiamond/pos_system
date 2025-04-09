<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\text;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faktur_pembelians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pembelian_id')->constrained('pembelians')->cascadeOnDelete();
            $table->foreignUuid('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->string('no_faktur');
            $table->date('tanggal_faktur');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('biaya_lainnya');
            $table->enum('diskon_mode', ['persen', 'nominal'])->default('persen');
            $table->unsignedBigInteger('diskon_value');
            $table->unsignedBigInteger('pajak_value');
            $table->unsignedBigInteger('total_tagihan');
            $table->unsignedBigInteger('total_bayar');
            $table->unsignedBigInteger('total_hutang');
            $table->enum('status', ['lunas', 'hutang']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktur_pembelians');
    }
};
