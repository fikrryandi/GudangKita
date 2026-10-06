<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Stok;
use App\Models\StockMovement;

/**
 * Service untuk mengelola stok barang.
 * Semua operasi stok menggunakan DB transaction dengan row-locking
 * untuk mencegah race condition.
 */
class StokService
{
    /**
     * Tambah stok (barang masuk / transfer_in / adjustment positif / opname).
     */
    public function tambah(
        int $barangId,
        int $departemenId,
        int $gedungId,
        int $qty,
        float $hargaSatuan,
        string $jenis,
        string $noTransaksi,
        int $userId
    ): Stok {
        return DB::transaction(function () use ($barangId, $departemenId, $gedungId, $qty, $hargaSatuan, $jenis, $noTransaksi, $userId) {
            // Lock row stok agar tidak ada race condition
            $stok = Stok::where('barang_id', $barangId)
                ->where('departemen_id', $departemenId)
                ->where('gedung_id', $gedungId)
                ->lockForUpdate()
                ->first();

            if (!$stok) {
                $stok = Stok::create([
                    'barang_id'    => $barangId,
                    'departemen_id' => $departemenId,
                    'gedung_id'    => $gedungId,
                    'qty'          => 0,
                ]);
            }

            $stok->increment('qty', $qty);
            $stok->refresh();

            // Catat stock movement
            StockMovement::create([
                'tanggal'       => now()->toDateString(),
                'no_transaksi'  => $noTransaksi,
                'jenis'         => $jenis,
                'barang_id'     => $barangId,
                'departemen_id' => $departemenId,
                'gedung_id'     => $gedungId,
                'qty_masuk'     => $qty,
                'qty_keluar'    => 0,
                'harga_satuan'  => $hargaSatuan,
                'saldo_qty'     => $stok->qty,
                'nilai_saldo'   => $stok->qty * $hargaSatuan,
                'user_id'       => $userId,
            ]);

            return $stok;
        });
    }

    /**
     * Kurangi stok (barang keluar / transfer_out / adjustment negatif).
     * Melempar exception jika stok tidak mencukupi.
     */
    public function kurangi(
        int $barangId,
        int $departemenId,
        int $gedungId,
        int $qty,
        float $hargaSatuan,
        string $jenis,
        string $noTransaksi,
        int $userId
    ): Stok {
        return DB::transaction(function () use ($barangId, $departemenId, $gedungId, $qty, $hargaSatuan, $jenis, $noTransaksi, $userId) {
            $stok = Stok::where('barang_id', $barangId)
                ->where('departemen_id', $departemenId)
                ->where('gedung_id', $gedungId)
                ->lockForUpdate()
                ->first();

            // Validasi stok cukup
            if (!$stok || $stok->qty < $qty) {
                $tersedia = $stok ? $stok->qty : 0;
                $satuan = $stok ? optional($stok->barang)->satuan : 'Unit';
                throw new \RuntimeException("Stok tidak mencukupi. Stok tersedia hanya {$tersedia} {$satuan}.");
            }

            $stok->decrement('qty', $qty);
            $stok->refresh();

            // Catat stock movement
            StockMovement::create([
                'tanggal'       => now()->toDateString(),
                'no_transaksi'  => $noTransaksi,
                'jenis'         => $jenis,
                'barang_id'     => $barangId,
                'departemen_id' => $departemenId,
                'gedung_id'     => $gedungId,
                'qty_masuk'     => 0,
                'qty_keluar'    => $qty,
                'harga_satuan'  => $hargaSatuan,
                'saldo_qty'     => $stok->qty,
                'nilai_saldo'   => $stok->qty * $hargaSatuan,
                'user_id'       => $userId,
            ]);

            return $stok;
        });
    }
}
