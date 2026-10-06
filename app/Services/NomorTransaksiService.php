<?php

namespace App\Services;

use App\Models\BarangMasuk;
use App\Models\RequestBarang;
use App\Models\Transfer;
use App\Models\Adjustment;
use App\Models\StockOpname;
use Illuminate\Support\Str;

/**
 * Service untuk membuat nomor transaksi otomatis.
 * Format: PREFIX-YYYYMMDD-XXX (3 digit sequential per hari)
 */
class NomorTransaksiService
{
    public function generate(string $prefix): string
    {
        $tanggal = now()->format('Ymd');
        $model   = $this->getModel($prefix);

        // Hitung jumlah transaksi hari ini untuk urutan
        $count = $model::where('no_transaksi', 'like', "{$prefix}-{$tanggal}-%")->count();
        $urutan = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$tanggal}-{$urutan}";
    }

    private function getModel(string $prefix)
    {
        return match ($prefix) {
            'BM'  => BarangMasuk::class,
            'BK'  => RequestBarang::class,
            'TRF' => Transfer::class,
            'ADJ' => Adjustment::class,
            'OPN' => StockOpname::class,
            default => throw new \InvalidArgumentException("Prefix transaksi tidak dikenal: {$prefix}"),
        };
    }
}
