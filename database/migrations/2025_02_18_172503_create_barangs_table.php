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
        Schema::create('barangs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kategori_id')->constrained()->nullable()->nullOnDelete();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->foreignUuid('satuan_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('harga_beli');
            $table->unsignedBigInteger('harga_pokok');
            $table->unsignedBigInteger('harga_jual');
            $table->decimal('markup', 5, 2);
            $table->decimal('diskon_value', 5, 2);
            $table->unsignedTinyInteger('diskon_nominal');
            $table->unsignedTinyInteger('stok_minimum');
            $table->unsignedBigInteger('stok');
            $table->foreignUuid('pajak_id')->constrained()->restrictOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('gambar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
