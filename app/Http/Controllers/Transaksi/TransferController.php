<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\Barang;
use App\Models\Departemen;
use App\Models\Gedung;
use App\Services\StokService;
use App\Services\NomorTransaksiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function __construct(
        private StokService $stokService,
        private NomorTransaksiService $nomorService
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Transfer::with(['departemenAsal', 'gedungAsal', 'departemenTujuan', 'gedungTujuan', 'barang', 'user'])->latest();

        if (!$user->hasRole('Super Admin')) {
            $query->where(function($q) use ($user) {
                $q->where('departemen_asal_id', $user->departemen_id)
                  ->orWhere('departemen_tujuan_id', $user->departemen_id);
            });
        }

        $records    = $query->paginate(15)->withQueryString();
        $departemen = Departemen::all();
        $gedung     = Gedung::all();

        return view('transaksi.transfer.index', compact('records', 'departemen', 'gedung'));
    }

    public function create()
    {
        $departemen = Departemen::all();
        $gedung     = Gedung::all();
        return view('transaksi.transfer.create', compact('departemen', 'gedung'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'departemen_asal_id'   => 'required|exists:departemen,id',
            'gedung_asal_id'       => 'required|exists:gedung,id',
            'departemen_tujuan_id' => 'required|exists:departemen,id',
            'gedung_tujuan_id'     => 'required|exists:gedung,id',
            'barang_id'            => 'required|exists:barang,id',
            'qty'                  => 'required|integer|min:1',
            'alasan'               => 'required|string|min:5',
        ]);

        // Validasi asal & tujuan tidak sama
        if ($validated['departemen_asal_id'] === $validated['departemen_tujuan_id'] &&
            $validated['gedung_asal_id'] === $validated['gedung_tujuan_id']) {
            return back()->withErrors(['gedung_tujuan_id' => 'Lokasi asal dan tujuan tidak boleh sama.'])->withInput();
        }

        $barang = Barang::findOrFail($validated['barang_id']);

        DB::transaction(function () use ($validated, $barang) {
            $noTrx = 'TRF-'.now()->format('Ymd').'-'.str_pad(Transfer::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);

            // Kurangi stok asal
            $this->stokService->kurangi(
                $barang->id,
                $validated['departemen_asal_id'],
                $validated['gedung_asal_id'],
                $validated['qty'],
                $barang->harga_satuan,
                'transfer_out',
                $noTrx,
                auth()->id()
            );

            // Tambah stok tujuan
            $this->stokService->tambah(
                $barang->id,
                $validated['departemen_tujuan_id'],
                $validated['gedung_tujuan_id'],
                $validated['qty'],
                $barang->harga_satuan,
                'transfer_in',
                $noTrx,
                auth()->id()
            );

            Transfer::create([
                'tanggal'             => now()->toDateString(),
                'departemen_asal_id'  => $validated['departemen_asal_id'],
                'gedung_asal_id'      => $validated['gedung_asal_id'],
                'departemen_tujuan_id' => $validated['departemen_tujuan_id'],
                'gedung_tujuan_id'    => $validated['gedung_tujuan_id'],
                'barang_id'           => $barang->id,
                'qty'                 => $validated['qty'],
                'harga_satuan'        => $barang->harga_satuan,
                'nilai'               => $validated['qty'] * $barang->harga_satuan,
                'alasan'              => $validated['alasan'],
                'user_id'             => auth()->id(),
            ]);

            activity()->causedBy(auth()->user())->log("Transfer barang {$barang->kode_barang} qty {$validated['qty']} — {$noTrx}");
        });

        return redirect()->route('transaksi.transfer.index')->with('success', 'Transfer berhasil dilakukan.');
    }
}
