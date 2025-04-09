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
        Schema::create('konversis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('barang_id')->constrained('barangs')->cascadeOnDelete()->nullable();
            $table->foreignUuid('satuan_id')->constrained('satuans')->cascadeOnDelete();
            $table->bigInteger('nilai_konversi');
            $table->foreignUuid('satuan_tujuan_id')->constrained('satuans')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konversis');
    }
};
