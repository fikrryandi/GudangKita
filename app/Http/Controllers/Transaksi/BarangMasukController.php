<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\Barang;
use App\Models\Departemen;
use App\Models\Gedung;
use App\Models\Supplier;
use App\Services\StokService;
use App\Services\NomorTransaksiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function __construct(
        private StokService $stokService,
        private NomorTransaksiService $nomorService
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = BarangMasuk::with(['departemen', 'gedung', 'supplier', 'user'])
            ->latest();

        if (!$user->hasRole('Super Admin')) {
            $query->where('departemen_id', $user->departemen_id);
        }

        if ($request->filled('search')) {
            $query->where('no_transaksi', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('departemen_id')) $query->where('departemen_id', $request->departemen_id);
        if ($request->filled('gedung_id'))     $query->where('gedung_id',     $request->gedung_id);
        if ($request->filled('dari'))          $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai'))        $query->whereDate('tanggal', '<=', $request->sampai);

        $records   = $query->paginate(15)->withQueryString();
        $departemen = Departemen::all();
        $gedung     = Gedung::all();

        return view('transaksi.barang-masuk.index', compact('records', 'departemen', 'gedung'));
    }

    public function create()
    {
        $departemen = Departemen::all();
        $gedung     = Gedung::all();
        $supplier   = Supplier::where('status', true)->get();
        return view('transaksi.barang-masuk.create', compact('departemen', 'gedung', 'supplier'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'departemen_id'   => 'required|exists:departemen,id',
            'gedung_id'       => 'required|exists:gedung,id',
            'supplier_id'     => 'nullable|exists:supplier,id',
            'no_surat_jalan'  => 'nullable|string|max:100',
            'tanggal'         => 'required|date',
            'kondisi'         => 'required|in:Baik,Rusak',
            'keterangan'      => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.barang_id'    => 'required|exists:barang,id',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $noTrx = $this->nomorService->generate('BM');

            $bm = BarangMasuk::create([
                'no_transaksi'  => $noTrx,
                'tanggal'       => $validated['tanggal'],
                'departemen_id' => $validated['departemen_id'],
                'gedung_id'     => $validated['gedung_id'],
                'supplier_id'   => $validated['supplier_id'] ?? null,
                'no_surat_jalan' => $validated['no_surat_jalan'] ?? null,
                'kondisi'       => $validated['kondisi'],
                'keterangan'    => $validated['keterangan'] ?? null,
                'user_id'       => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $barang = Barang::findOrFail($item['barang_id']);
                $total  = $item['qty'] * $item['harga_satuan'];

                BarangMasukDetail::create([
                    'barang_masuk_id' => $bm->id,
                    'barang_id'       => $item['barang_id'],
                    'qty'             => $item['qty'],
                    'harga_satuan'    => $item['harga_satuan'],
                    'total_nilai'     => $total,
                ]);

                // Tambah stok
                $this->stokService->tambah(
                    $item['barang_id'],
                    $validated['departemen_id'],
                    $validated['gedung_id'],
                    $item['qty'],
                    $item['harga_satuan'],
                    'masuk',
                    $noTrx,
                    auth()->id()
                );
            }

            activity()->causedBy(auth()->user())
                ->performedOn($bm)
                ->withProperties(['no_transaksi' => $noTrx])
                ->log("Barang masuk {$noTrx} dicatat");
        });

        return redirect()->route('transaksi.barang-masuk.index')
            ->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load(['departemen', 'gedung', 'supplier', 'user', 'details.barang.kategori']);
        return view('transaksi.barang-masuk.show', compact('barangMasuk'));
    }
}
