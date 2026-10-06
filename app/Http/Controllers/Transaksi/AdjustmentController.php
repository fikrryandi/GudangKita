<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Adjustment;
use App\Models\Barang;
use App\Models\Departemen;
use App\Models\Gedung;
use App\Services\StokService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdjustmentController extends Controller
{
    public function __construct(private StokService $stokService) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Adjustment::with(['barang', 'departemen', 'gedung', 'user'])->latest();

        if (!$user->hasRole('Super Admin')) {
            $query->where('departemen_id', $user->departemen_id);
        }

        $records    = $query->paginate(15)->withQueryString();
        $departemen = Departemen::all();
        $gedung     = Gedung::all();
        return view('transaksi.adjustment.index', compact('records', 'departemen', 'gedung'));
    }

    public function create()
    {
        $departemen = Departemen::all();
        $gedung     = Gedung::all();
        return view('transaksi.adjustment.create', compact('departemen', 'gedung'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'         => 'required|in:rusak,hilang,selisih,koreksi input,kadaluarsa',
            'barang_id'     => 'required|exists:barang,id',
            'departemen_id' => 'required|exists:departemen,id',
            'gedung_id'     => 'required|exists:gedung,id',
            'qty'           => 'required|integer|min:1',
            'alasan'        => 'required|string|min:5',
        ]);

        $barang = Barang::findOrFail($validated['barang_id']);

        DB::transaction(function () use ($validated, $barang) {
            $noTrx = 'ADJ-'.now()->format('Ymd').'-'.str_pad(Adjustment::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
            $nilaiKerugian = $validated['qty'] * $barang->harga_satuan;

            // Kurangi stok
            $this->stokService->kurangi(
                $barang->id,
                $validated['departemen_id'],
                $validated['gedung_id'],
                $validated['qty'],
                $barang->harga_satuan,
                'adjustment',
                $noTrx,
                auth()->id()
            );

            Adjustment::create([
                'tanggal'       => now()->toDateString(),
                'jenis'         => $validated['jenis'],
                'barang_id'     => $barang->id,
                'departemen_id' => $validated['departemen_id'],
                'gedung_id'     => $validated['gedung_id'],
                'qty'           => $validated['qty'],
                'harga_satuan'  => $barang->harga_satuan,
                'nilai_kerugian' => $nilaiKerugian,
                'alasan'        => $validated['alasan'],
                'user_id'       => auth()->id(),
            ]);

            activity()->causedBy(auth()->user())->log("Stock Adjustment {$noTrx}: {$barang->kode_barang} -qty {$validated['qty']}");
        });

        return redirect()->route('transaksi.adjustment.index')->with('success', 'Stock adjustment berhasil dicatat.');
    }
}
