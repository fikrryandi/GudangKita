<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Barang;
use App\Models\Departemen;
use App\Models\Gedung;
use Illuminate\Http\Request;

class KartuStokController extends Controller
{
    public function index(Request $request)
    {
        $departemen = Departemen::all();
        $gedung     = Gedung::all();
        $barang     = Barang::where('is_aktif', true)->with('departemen')->get();
        $movements  = null;
        $selected   = null;

        if ($request->filled('barang_id') && $request->filled('departemen_id') && $request->filled('gedung_id')) {
            $movements = StockMovement::with(['user', 'barang'])
                ->where('barang_id',     $request->barang_id)
                ->where('departemen_id', $request->departemen_id)
                ->where('gedung_id',     $request->gedung_id)
                ->orderBy('tanggal')
                ->orderBy('id')
                ->paginate(25)->withQueryString();

            $selected = [
                'barang'     => Barang::with('kategori')->find($request->barang_id),
                'departemen' => Departemen::find($request->departemen_id),
                'gedung'     => Gedung::find($request->gedung_id),
            ];
        }

        return view('inventory.kartu-stok.index', compact('departemen', 'gedung', 'barang', 'movements', 'selected'));
    }
}
