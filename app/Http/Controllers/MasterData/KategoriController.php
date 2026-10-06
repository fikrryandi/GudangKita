<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('barang')->latest()->paginate(15);
        return view('master-data.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('master-data.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_prefix' => 'required|string|max:10|unique:kategori',
            'nama'        => 'required|string|max:100',
        ]);
        $validated['kode_prefix'] = strtoupper($validated['kode_prefix']);
        Kategori::create($validated);
        return redirect()->route('master.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('master-data.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'kode_prefix' => 'required|string|max:10|unique:kategori,kode_prefix,'.$kategori->id,
            'nama'        => 'required|string|max:100',
        ]);
        $kategori->update($validated);
        return redirect()->route('master.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
