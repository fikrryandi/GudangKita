<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\Barang;
use App\Models\Departemen;
use App\Models\Gedung;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Stok::with(['barang.kategori', 'departemen', 'gedung']);

        if (!$user->hasRole('Super Admin')) {
            $query->where('departemen_id', $user->departemen_id);
        }

        if ($request->filled('departemen_id')) $query->where('departemen_id', $request->departemen_id);
        if ($request->filled('gedung_id'))     $query->where('gedung_id',     $request->gedung_id);
        if ($request->filled('search')) {
            $query->whereHas('barang', fn($q) => $q->where('nama', 'like', '%'.$request->search.'%')
                ->orWhere('kode_barang', 'like', '%'.$request->search.'%'));
        }
        if ($request->filled('status')) {
            match ($request->status) {
                'aman'    => $query->whereHas('barang', fn($q) => $q->whereRaw('stok.qty > barang.minimum_stock')),
                'menipis' => $query->whereHas('barang', fn($q) => $q->whereRaw('stok.qty > 0 AND stok.qty <= barang.minimum_stock')),
                'habis'   => $query->where('qty', 0),
                default   => null,
            };
        }

        $stok       = $query->paginate(20)->withQueryString();
        $departemen = Departemen::all();
        $gedung     = Gedung::all();

        return view('inventory.stok.index', compact('stok', 'departemen', 'gedung'));
    }
}
