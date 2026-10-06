<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\RequestBarang;
use App\Models\Nota;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Nota::with(['requestBarang.departemen', 'requestBarang.gedung', 'requestBarang.peminta', 'dicetakOleh'])
            ->latest();

        $records = $query->paginate(15)->withQueryString();
        return view('transaksi.nota.index', compact('records'));
    }

    public function show(RequestBarang $requestBarang)
    {
        if ($requestBarang->status !== 'Selesai') {
            return back()->with('error', 'Nota hanya dapat dicetak jika status request adalah Selesai.');
        }

        $requestBarang->load(['departemen', 'gedung', 'peminta', 'approver', 'details.barang']);

        // Buat atau ambil nota yang sudah ada
        $nota = Nota::firstOrCreate(
            ['request_barang_id' => $requestBarang->id],
            [
                'no_transaksi' => 'NTA-'.$requestBarang->no_transaksi,
                'dicetak_oleh' => auth()->id(),
                'waktu_cetak'  => now(),
                'snapshot_data' => json_encode($requestBarang->toArray()),
            ]
        );

        if (!$nota->wasRecentlyCreated) {
            $nota->update(['dicetak_oleh' => auth()->id(), 'waktu_cetak' => now()]);
        }

        return view('transaksi.nota.show', compact('requestBarang', 'nota'));
    }

    public function cetak(RequestBarang $requestBarang)
    {
        if ($requestBarang->status !== 'Selesai') {
            abort(403, 'Nota hanya dapat dicetak jika status request adalah Selesai.');
        }

        $requestBarang->load(['departemen', 'gedung', 'peminta', 'approver', 'details.barang']);
        $nota = Nota::firstOrCreate(
            ['request_barang_id' => $requestBarang->id],
            [
                'no_transaksi' => 'NTA-'.$requestBarang->no_transaksi,
                'dicetak_oleh' => auth()->id(),
                'waktu_cetak'  => now(),
                'snapshot_data' => json_encode($requestBarang->toArray()),
            ]
        );

        $pdf = Pdf::loadView('pdf.nota', compact('requestBarang', 'nota'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("Nota-{$requestBarang->no_transaksi}.pdf");
    }
}
