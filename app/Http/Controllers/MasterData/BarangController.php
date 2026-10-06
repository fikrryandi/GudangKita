<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Departemen;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Barang::with(['kategori', 'departemen']);

        // Admin departemen hanya lihat barang departemennya
        if (!$user->hasRole('Super Admin')) {
            $query->where('departemen_id', $user->departemen_id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('kode_barang', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('departemen_id')) $query->where('departemen_id', $request->departemen_id);
        if ($request->filled('kategori_id'))  $query->where('kategori_id',  $request->kategori_id);
        if ($request->filled('status') && $request->status !== '') {
            $query->where('is_aktif', $request->status);
        }

        $barang = $query->latest()->paginate(15)->withQueryString();
        $kategori = Kategori::all();
        $departemen = Departemen::all();

        return view('master-data.barang.index', compact('barang', 'kategori', 'departemen'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $departemen = Departemen::all();
        return view('master-data.barang.create', compact('kategori', 'departemen'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:150',
            'kategori_id'   => 'required|exists:kategori,id',
            'departemen_id' => 'required|exists:departemen,id',
            'satuan'        => 'required|string|max:20',
            'harga_satuan'  => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'deskripsi'     => 'nullable|string',
        ]);

        // Generate kode barang otomatis: PREFIX-XXX
        $kategori = Kategori::findOrFail($validated['kategori_id']);
        $prefix   = $kategori->kode_prefix;
        $lastCode = Barang::where('kode_barang', 'like', "{$prefix}-%")
            ->orderByDesc('id')->value('kode_barang');
        $urutan = $lastCode ? ((int) substr($lastCode, strlen($prefix)+1) + 1) : 1;
        $validated['kode_barang'] = $prefix.'-'.str_pad($urutan, 3, '0', STR_PAD_LEFT);
        $validated['is_aktif'] = true;

        Barang::create($validated);

        activity()->causedBy(auth()->user())->log("Menambahkan barang baru: {$validated['kode_barang']} - {$validated['nama']}");

        return redirect()->route('master.barang.index')->with('success', "Barang {$validated['kode_barang']} berhasil ditambahkan.");
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        $departemen = Departemen::all();
        return view('master-data.barang.edit', compact('barang', 'kategori', 'departemen'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:150',
            'kategori_id'   => 'required|exists:kategori,id',
            'departemen_id' => 'required|exists:departemen,id',
            'satuan'        => 'required|string|max:20',
            'harga_satuan'  => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'deskripsi'     => 'nullable|string',
            'is_aktif'      => 'boolean',
        ]);

        $before = $barang->toArray();
        $barang->update($validated);

        activity()->causedBy(auth()->user())
            ->performedOn($barang)
            ->withProperties(['before' => $before, 'after' => $barang->toArray()])
            ->log("Mengubah data barang: {$barang->kode_barang}");

        return redirect()->route('master.barang.index')->with('success', "Data barang {$barang->kode_barang} berhasil diperbarui.");
    }

    public function destroy(Barang $barang)
    {
        $kode = $barang->kode_barang;
        $barang->update(['is_aktif' => false]);
        activity()->causedBy(auth()->user())->log("Menonaktifkan barang: {$kode}");
        return back()->with('success', "Barang {$kode} berhasil dinonaktifkan.");
    }

    /** JSON endpoint untuk Alpine.js dropdown */
    public function byDepartemen(int $departemenId)
    {
        $barang = Barang::with('kategori')
            ->where('departemen_id', $departemenId)
            ->where('is_aktif', true)
            ->get()
            ->map(fn($b) => [
                'id'           => $b->id,
                'kode_barang'  => $b->kode_barang,
                'nama'         => $b->nama,
                'kategori'     => $b->kategori->nama,
                'satuan'       => $b->satuan,
                'harga_satuan' => $b->harga_satuan,
            ]);

        return response()->json($barang);
    }

    /** JSON endpoint untuk mengambil semua barang aktif */
    public function apiAll()
    {
        $barang = Barang::with('kategori')
            ->where('is_aktif', true)
            ->get()
            ->map(fn($b) => [
                'id'           => $b->id,
                'kode_barang'  => $b->kode_barang,
                'nama'         => $b->nama,
                'kategori'     => optional($b->kategori)->nama,
                'satuan'       => $b->satuan,
                'harga_satuan' => $b->harga_satuan,
            ]);

        return response()->json($barang);
    }
}
