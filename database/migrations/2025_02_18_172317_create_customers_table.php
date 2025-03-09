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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("kode")->unique();
            $table->string("nama");
            $table->string("alamat");
            $table->string("telepon");
            $table->string("email");
            $table->date("tanggal_lahit");
            $table->enum("status", ["active", "inactive"]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
