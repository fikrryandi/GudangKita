<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::latest();
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%'.$request->search.'%')->orWhere('kode_supplier', 'like', '%'.$request->search.'%');
        }
        $supplier = $query->paginate(15)->withQueryString();
        return view('master-data.supplier.index', compact('supplier'));
    }

    public function create()
    {
        return view('master-data.supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:150',
            'alamat'       => 'nullable|string',
            'telepon'      => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:100',
            'pic'          => 'nullable|string|max:100',
        ]);

        // Generate kode supplier
        $last = Supplier::orderByDesc('id')->value('kode_supplier');
        $urutan = $last ? ((int) substr($last, 4) + 1) : 1;
        $validated['kode_supplier'] = 'SUP-'.str_pad($urutan, 3, '0', STR_PAD_LEFT);
        $validated['status'] = true;

        Supplier::create($validated);
        return redirect()->route('master.supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('master-data.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:150',
            'alamat'  => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:100',
            'pic'     => 'nullable|string|max:100',
            'status'  => 'boolean',
        ]);
        $supplier->update($validated);
        return redirect()->route('master.supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->update(['status' => false]);
        return back()->with('success', 'Supplier dinonaktifkan.');
    }
}
