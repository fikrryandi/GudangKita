<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\RequestBarang;
use App\Models\Transfer;
use App\Models\Adjustment;
use App\Models\StockOpname;
use App\Models\Stok;
use App\Models\Nota;
use App\Models\Departemen;
use App\Models\Gedung;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $departemen = Departemen::all();
        $gedung     = Gedung::all();
        return view('laporan.index', compact('departemen', 'gedung'));
    }

    public function barangMasuk(Request $request)
    {
        $query = BarangMasuk::with(['departemen', 'gedung', 'supplier', 'user', 'details.barang'])
            ->filter($request->only(['departemen_id', 'gedung_id', 'dari', 'sampai']))
            ->latest();

        if (!auth()->user()->hasRole('Super Admin')) {
            $query->where('departemen_id', auth()->user()->departemen_id);
        }

        $records    = $query->get();
        $departemen = Departemen::all();
        $gedung     = Gedung::all();

        if ($request->filled('export_excel')) {
            return Excel::download(new \App\Exports\BarangMasukExport($records), 'laporan-barang-masuk.xlsx');
        }
        if ($request->filled('export_pdf')) {
            $pdf = Pdf::loadView('pdf.laporan-barang-masuk', compact('records'))->setPaper('A4', 'landscape');
            return $pdf->download('laporan-barang-masuk.pdf');
        }

        return view('laporan.barang-masuk', compact('records', 'departemen', 'gedung'));
    }

    public function barangKeluar(Request $request)
    {
        $query = RequestBarang::with(['departemen', 'gedung', 'peminta', 'approver', 'details.barang'])
            ->whereIn('status', ['Diproses', 'Selesai']);

        if (!auth()->user()->hasRole('Super Admin')) {
            $query->where('departemen_id', auth()->user()->departemen_id);
        }
        if ($request->filled('dari'))  $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $query->whereDate('tanggal', '<=', $request->sampai);

        $records    = $query->latest()->get();
        $departemen = Departemen::all();
        $gedung     = Gedung::all();

        if ($request->filled('export_pdf')) {
            $pdf = Pdf::loadView('pdf.laporan-barang-keluar', compact('records'))->setPaper('A4', 'landscape');
            return $pdf->download('laporan-barang-keluar.pdf');
        }

        return view('laporan.barang-keluar', compact('records', 'departemen', 'gedung'));
    }

    public function stok(Request $request)
    {
        $query = Stok::with(['barang.kategori', 'departemen', 'gedung']);

        if (!auth()->user()->hasRole('Super Admin')) {
            $query->where('departemen_id', auth()->user()->departemen_id);
        }
        if ($request->filled('departemen_id')) $query->where('departemen_id', $request->departemen_id);
        if ($request->filled('gedung_id'))     $query->where('gedung_id',     $request->gedung_id);

        $records    = $query->get();
        $departemen = Departemen::all();
        $gedung     = Gedung::all();

        if ($request->filled('export_pdf')) {
            $pdf = Pdf::loadView('pdf.laporan-stok', compact('records'))->setPaper('A4');
            return $pdf->download('laporan-stok.pdf');
        }

        return view('laporan.stok', compact('records', 'departemen', 'gedung'));
    }

    public function nota(Request $request)
    {
        $records    = Nota::with(['requestBarang.peminta', 'requestBarang.approver', 'requestBarang.departemen', 'dicetakOleh'])->latest()->get();
        return view('laporan.nota', compact('records'));
    }
}
