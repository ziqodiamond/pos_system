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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_ref');
            $table->date('tanggal_pembelian');
            $table->date('tanggal_masuk');
            $table->foreignUuid('supplier_id')->constrained('suppliers');
            $table->foreignUuid('user_id')->constrained('users');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('subtotal');
            $table->enum('diskon_mode', ['persen', 'nominal'])->default('persen');
            $table->unsignedBigInteger('diskon_value');
            $table->unsignedBigInteger('pajak_value');
            $table->unsignedBigInteger('biaya_lainnya');
            $table->unsignedBigInteger('total');
            $table->enum('status', ['processing', 'received', 'completed'])->default('processing');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
