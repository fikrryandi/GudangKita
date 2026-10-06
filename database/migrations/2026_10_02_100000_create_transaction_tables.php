<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50)->unique();
            $table->string('nama', 150);
            $table->foreignId('kategori_id')->constrained('kategori');
            $table->foreignId('departemen_id')->constrained('departemen');
            $table->string('satuan', 20);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang');
            $table->foreignId('departemen_id')->constrained('departemen');
            $table->foreignId('gedung_id')->constrained('gedung');
            $table->integer('qty')->default(0);
            $table->timestamps();
            
            $table->unique(['barang_id', 'departemen_id', 'gedung_id']);
        });

        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 50)->unique(); // BM-YYYYMMDD-XXX
            $table->date('tanggal');
            $table->foreignId('departemen_id')->constrained('departemen');
            $table->foreignId('gedung_id')->constrained('gedung');
            $table->foreignId('supplier_id')->nullable()->constrained('supplier');
            $table->string('no_surat_jalan', 100)->nullable();
            $table->string('kondisi', 50)->default('Baik'); // Baik/Rusak
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('barang_masuk_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang');
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_nilai', 15, 2);
            $table->timestamps();
        });

        Schema::create('request_barang', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 50)->unique(); // BK-YYYYMMDD-XXX
            $table->date('tanggal');
            $table->foreignId('departemen_id')->constrained('departemen');
            $table->foreignId('gedung_id')->constrained('gedung');
            $table->foreignId('peminta_user_id')->constrained('users');
            $table->text('keperluan');
            $table->text('catatan')->nullable();
            $table->string('status', 50)->default('Menunggu Approval'); // Menunggu Approval / Diproses / Ditolak / Selesai
            $table->foreignId('approver_id')->nullable()->constrained('users');
            $table->text('alasan_reject')->nullable();
            $table->timestamp('waktu_approve')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();
        });

        Schema::create('request_barang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_barang_id')->constrained('request_barang')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang');
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        Schema::create('stock_movement', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('no_transaksi', 50);
            $table->string('jenis', 50); // masuk/keluar/transfer_in/transfer_out/adjustment/opname
            $table->foreignId('barang_id')->constrained('barang');
            $table->foreignId('departemen_id')->constrained('departemen');
            $table->foreignId('gedung_id')->constrained('gedung');
            $table->integer('qty_masuk')->default(0);
            $table->integer('qty_keluar')->default(0);
            $table->decimal('harga_satuan', 15, 2);
            $table->integer('saldo_qty');
            $table->decimal('nilai_saldo', 15, 2);
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('transfer', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('departemen_asal_id')->constrained('departemen');
            $table->foreignId('gedung_asal_id')->constrained('gedung');
            $table->foreignId('departemen_tujuan_id')->constrained('departemen');
            $table->foreignId('gedung_tujuan_id')->constrained('gedung');
            $table->foreignId('barang_id')->constrained('barang');
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('nilai', 15, 2);
            $table->text('alasan');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('adjustment', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('jenis', 50); // rusak/hilang/selisih/koreksi input/kadaluarsa
            $table->foreignId('barang_id')->constrained('barang');
            $table->foreignId('departemen_id')->constrained('departemen');
            $table->foreignId('gedung_id')->constrained('gedung');
            $table->integer('qty'); // bisa minus atau plus, simpan nilai aslinya atau mutlaknya
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('nilai_kerugian', 15, 2);
            $table->text('alasan');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('stock_opname', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('departemen_id')->constrained('departemen');
            $table->foreignId('gedung_id')->constrained('gedung');
            $table->string('status', 50)->default('Draft');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('stock_opname_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained('stock_opname')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang');
            $table->integer('stok_sistem');
            $table->integer('stok_fisik');
            $table->integer('selisih');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('nilai_selisih', 15, 2);
            $table->boolean('is_applied')->default(false);
            $table->timestamps();
        });

        Schema::create('nota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_barang_id')->constrained('request_barang');
            $table->string('no_transaksi', 50)->unique();
            $table->foreignId('dicetak_oleh')->constrained('users');
            $table->timestamp('waktu_cetak');
            $table->longText('snapshot_data')->nullable(); // json snapshot
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('nota');
        Schema::dropIfExists('stock_opname_detail');
        Schema::dropIfExists('stock_opname');
        Schema::dropIfExists('adjustment');
        Schema::dropIfExists('transfer');
        Schema::dropIfExists('stock_movement');
        Schema::dropIfExists('request_barang_detail');
        Schema::dropIfExists('request_barang');
        Schema::dropIfExists('barang_masuk_detail');
        Schema::dropIfExists('barang_masuk');
        Schema::dropIfExists('stok');
        Schema::dropIfExists('barang');
    }
};
