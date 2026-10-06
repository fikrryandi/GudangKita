<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\RequestBarang;
use App\Models\RequestBarangDetail;
use App\Models\Barang;
use App\Models\Departemen;
use App\Models\Gedung;
use App\Models\Stok;
use App\Services\NomorTransaksiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\RequestBaru;

class RequestBarangController extends Controller
{
    public function __construct(private NomorTransaksiService $nomorService) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = RequestBarang::with(['departemen', 'gedung', 'peminta'])
            ->where('peminta_user_id', $user->id)
            ->latest();

        $records    = $query->paginate(15)->withQueryString();
        $departemen = Departemen::all();
        $gedung     = Gedung::all();

        return view('transaksi.request.index', compact('records', 'departemen', 'gedung'));
    }

    public function create()
    {
        $departemen = Departemen::all();
        $gedung     = Gedung::all();
        return view('transaksi.request.create', compact('departemen', 'gedung'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'gedung_id'     => 'required|exists:gedung,id',
            'keperluan'     => 'required|string',
            'catatan'       => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty'       => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $noTrx = $this->nomorService->generate('BK');

            $req = RequestBarang::create([
                'no_transaksi'   => $noTrx,
                'tanggal'        => now()->toDateString(),
                'departemen_id'  => $validated['departemen_id'],
                'gedung_id'      => $validated['gedung_id'],
                'peminta_user_id' => auth()->id(),
                'keperluan'      => $validated['keperluan'],
                'catatan'        => $validated['catatan'] ?? null,
                'status'         => 'Menunggu Approval',
            ]);

            foreach ($validated['items'] as $item) {
                $barang = Barang::findOrFail($item['barang_id']);
                RequestBarangDetail::create([
                    'request_barang_id' => $req->id,
                    'barang_id'         => $item['barang_id'],
                    'qty'               => $item['qty'],
                    'harga_satuan'      => $barang->harga_satuan,
                    'subtotal'          => $item['qty'] * $barang->harga_satuan,
                ]);
            }

            // Notifikasi ke Admin departemen
            $admins = \App\Models\User::role(['Admin HRGA','Admin EHS','Admin MTC','Super Admin'])
                ->where(function($q) use ($validated) {
                    $q->where('departemen_id', $validated['departemen_id'])
                      ->orWhereNull('departemen_id'); // Super Admin
                })->get();

            foreach ($admins as $admin) {
                $admin->notify(new RequestBaru($req));
            }

            activity()->causedBy(auth()->user())
                ->performedOn($req)
                ->log("Request barang baru: {$noTrx}");
        });

        return redirect()->route('transaksi.request.index')
            ->with('success', 'Request barang berhasil dikirim.');
    }

    public function show(RequestBarang $requestBarang)
    {
        $requestBarang->load(['departemen', 'gedung', 'peminta', 'approver', 'details.barang']);
        return view('transaksi.request.show', compact('requestBarang'));
    }

    /** Ambil stok tersedia untuk item (JSON) */
    public function stokTersedia(Request $request)
    {
        $stok = Stok::where('barang_id', $request->barang_id)
            ->where('departemen_id', $request->departemen_id)
            ->where('gedung_id', $request->gedung_id)
            ->value('qty');

        return response()->json(['stok' => $stok ?? 0]);
    }
}
