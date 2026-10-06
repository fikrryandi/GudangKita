<?php

namespace App\Services;

use App\Models\RequestBarang;
use App\Models\Stok;
use App\Models\Nota;
use App\Notifications\RequestDisetujui;
use App\Notifications\RequestDitolak;
use App\Notifications\NotaSiap;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk alur approval request barang.
 */
class ApprovalService
{
    public function __construct(private StokService $stokService) {}

    /**
     * Admin menyetujui request barang.
     * Validasi stok dilakukan per item. Jika gagal, otomatis ditolak.
     */
    public function approve(RequestBarang $request, int $approverId): void
    {
        DB::transaction(function () use ($request, $approverId) {
            // Cek kecukupan stok semua item terlebih dahulu
            foreach ($request->details as $detail) {
                $stok = Stok::where('barang_id', $detail->barang_id)
                    ->where('departemen_id', $request->departemen_id)
                    ->where('gedung_id', $request->gedung_id)
                    ->lockForUpdate()
                    ->first();

                if (!$stok || $stok->qty < $detail->qty) {
                    // Stok tidak cukup — tolak otomatis
                    $tersedia = $stok ? $stok->qty : 0;
                    $this->reject($request, $approverId, "Stok tidak mencukupi untuk barang {$detail->barang->nama}. Stok tersedia hanya {$tersedia} {$detail->barang->satuan}.");
                    return;
                }
            }

            // Kurangi stok semua item
            foreach ($request->details as $detail) {
                $this->stokService->kurangi(
                    $detail->barang_id,
                    $request->departemen_id,
                    $request->gedung_id,
                    $detail->qty,
                    $detail->harga_satuan,
                    'keluar',
                    $request->no_transaksi,
                    $approverId
                );
            }

            // Update status request
            $request->update([
                'status'       => 'Diproses',
                'approver_id'  => $approverId,
                'waktu_approve' => now(),
            ]);

            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($request)
                ->withProperties(['status' => 'Disetujui'])
                ->log("Request {$request->no_transaksi} disetujui");

            // Notifikasi ke peminta
            $request->peminta->notify(new RequestDisetujui($request));
        });
    }

    /**
     * Admin menolak request barang.
     */
    public function reject(RequestBarang $request, int $approverId, string $alasan): void
    {
        $request->update([
            'status'       => 'Ditolak',
            'approver_id'  => $approverId,
            'alasan_reject' => $alasan,
            'waktu_approve' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($request)
            ->withProperties(['alasan' => $alasan])
            ->log("Request {$request->no_transaksi} ditolak");

        $request->peminta->notify(new RequestDitolak($request));
    }

    /**
     * Admin menandai barang sudah diserahkan → status Selesai.
     */
    public function selesai(RequestBarang $request, int $approverId): void
    {
        $request->update([
            'status'       => 'Selesai',
            'waktu_selesai' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($request)
            ->log("Request {$request->no_transaksi} diselesaikan / barang diserahkan");

        // Notifikasi nota siap
        $request->peminta->notify(new NotaSiap($request));
    }
}
