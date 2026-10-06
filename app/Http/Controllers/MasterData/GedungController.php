<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    public function index()
    {
        $gedung = Gedung::latest()->paginate(15);
        return view('master-data.gedung.index', compact('gedung'));
    }

    public function create()
    {
        return view('master-data.gedung.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['nama' => 'required|string|max:100']);
        Gedung::create($validated);
        return redirect()->route('master.gedung.index')->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function edit(Gedung $gedung)
    {
        return view('master-data.gedung.edit', compact('gedung'));
    }

    public function update(Request $request, Gedung $gedung)
    {
        $validated = $request->validate(['nama' => 'required|string|max:100']);
        $gedung->update($validated);
        return redirect()->route('master.gedung.index')->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy(Gedung $gedung)
    {
        $gedung->delete();
        return back()->with('success', 'Gedung dihapus.');
    }
}
