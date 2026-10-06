<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departemen', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique(); // HRGA, EHS, MTC
            $table->string('nama', 100);
            $table->timestamps();
        });

        Schema::create('gedung', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100); // Gedung 1, Gedung 2
            $table->timestamps();
        });

        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('kode_prefix', 10)->unique(); // ATK, APD, SPR, TLS
            $table->string('nama', 100);
            $table->timestamps();
        });

        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->string('kode_supplier', 50)->unique();
            $table->string('nama', 150);
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('pic', 100)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('gedung');
        Schema::dropIfExists('departemen');
    }
};
