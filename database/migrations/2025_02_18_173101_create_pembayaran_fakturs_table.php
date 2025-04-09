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
        Schema::create('pembayaran_fakturs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('faktur_id')->constrained('faktur_pembelians')->cascadeOnDelete();
            $table->date('tanggal_pembayaran');
            $table->unsignedBigInteger('jumlah_pembayaran');
            $table->string('metode_pembayaran');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_fakturs');
    }
};
