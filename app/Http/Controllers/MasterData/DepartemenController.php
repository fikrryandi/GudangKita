<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    public function index()
    {
        $departemen = Departemen::latest()->paginate(15);
        return view('master-data.departemen.index', compact('departemen'));
    }

    public function create()
    {
        return view('master-data.departemen.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:departemen',
            'nama' => 'required|string|max:100',
        ]);
        $validated['kode'] = strtoupper($validated['kode']);
        Departemen::create($validated);
        return redirect()->route('master.departemen.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Departemen $departemen)
    {
        return view('master-data.departemen.edit', compact('departemen'));
    }

    public function update(Request $request, Departemen $departemen)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:departemen,kode,'.$departemen->id,
            'nama' => 'required|string|max:100',
        ]);
        $departemen->update($validated);
        return redirect()->route('master.departemen.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Departemen $departemen)
    {
        $departemen->delete();
        return back()->with('success', 'Departemen dihapus.');
    }
}
