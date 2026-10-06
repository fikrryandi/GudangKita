<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\Stok;
use App\Models\Departemen;
use App\Models\Gedung;
use App\Services\StokService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function __construct(private StokService $stokService) {}

    public function index(Request $request)
    {
        $departemen = Departemen::all();
        $gedung     = Gedung::all();
        $opname     = StockOpname::with(['departemen', 'gedung', 'user'])->latest()->paginate(15);

        // Jika filter dipilih, tampilkan form input stok fisik
        $stokList = null;
        if ($request->filled('departemen_id') && $request->filled('gedung_id')) {
            $stokList = Stok::with('barang')
                ->where('departemen_id', $request->departemen_id)
                ->where('gedung_id',     $request->gedung_id)
                ->get();
        }

        return view('inventory.stock-opname.index', compact('opname', 'departemen', 'gedung', 'stokList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'departemen_id'         => 'required|exists:departemen,id',
            'gedung_id'             => 'required|exists:gedung,id',
            'keterangan'            => 'nullable|string',
            'items'                 => 'required|array',
            'items.*.barang_id'     => 'required|exists:barang,id',
            'items.*.stok_fisik'    => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $opname = StockOpname::create([
                'tanggal'       => now()->toDateString(),
                'departemen_id' => $validated['departemen_id'],
                'gedung_id'     => $validated['gedung_id'],
                'keterangan'    => $validated['keterangan'] ?? null,
                'status'        => 'Draft',
                'user_id'       => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $stok = Stok::where('barang_id', $item['barang_id'])
                    ->where('departemen_id', $validated['departemen_id'])
                    ->where('gedung_id',     $validated['gedung_id'])
                    ->first();

                $stokSistem  = $stok ? $stok->qty : 0;
                $stokFisik   = $item['stok_fisik'];
                $selisih     = $stokFisik - $stokSistem;
                $harga       = optional(optional($stok)->barang)->harga_satuan ?? 0;
                $nilaiSelisih = abs($selisih) * $harga;

                StockOpnameDetail::create([
                    'stock_opname_id' => $opname->id,
                    'barang_id'       => $item['barang_id'],
                    'stok_sistem'     => $stokSistem,
                    'stok_fisik'      => $stokFisik,
                    'selisih'         => $selisih,
                    'harga_satuan'    => $harga,
                    'nilai_selisih'   => $nilaiSelisih,
                    'is_applied'      => false,
                ]);
            }

            activity()->causedBy(auth()->user())->log("Stock Opname dilakukan untuk Dept #{$validated['departemen_id']} Gedung #{$validated['gedung_id']}");
        });

        return redirect()->route('inventory.stock-opname.index')->with('success', 'Stock Opname berhasil disimpan.');
    }

    public function apply(StockOpname $stockOpname)
    {
        DB::transaction(function () use ($stockOpname) {
            $noTrx = 'OPN-'.now()->format('Ymd').'-'.str_pad(StockOpname::whereDate('created_at', today())->count(), 3, '0', STR_PAD_LEFT);

            foreach ($stockOpname->details as $detail) {
                if ($detail->is_applied || $detail->selisih === 0) continue;

                // Terapkan selisih ke stok
                if ($detail->selisih > 0) {
                    $this->stokService->tambah($detail->barang_id, $stockOpname->departemen_id, $stockOpname->gedung_id, $detail->selisih, $detail->harga_satuan, 'opname', $noTrx, auth()->id());
                } else {
                    $this->stokService->kurangi($detail->barang_id, $stockOpname->departemen_id, $stockOpname->gedung_id, abs($detail->selisih), $detail->harga_satuan, 'opname', $noTrx, auth()->id());
                }
                $detail->update(['is_applied' => true]);
            }

            $stockOpname->update(['status' => 'Diterapkan']);
        });

        return back()->with('success', 'Selisih opname berhasil diterapkan ke stok.');
    }
}
