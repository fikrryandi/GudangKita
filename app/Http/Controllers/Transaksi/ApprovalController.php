<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\RequestBarang;
use App\Models\Departemen;
use App\Services\ApprovalService;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct(private ApprovalService $approvalService) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = RequestBarang::with(['departemen', 'gedung', 'peminta', 'approver'])->latest();

        if (!$user->hasRole('Super Admin')) {
            $query->where('departemen_id', $user->departemen_id);
        }

        if ($request->filled('status'))       $query->where('status', $request->status);
        if ($request->filled('departemen_id')) $query->where('departemen_id', $request->departemen_id);
        if ($request->filled('search'))       $query->where('no_transaksi', 'like', '%'.$request->search.'%');

        $records    = $query->paginate(15)->withQueryString();
        $departemen = Departemen::all();

        return view('transaksi.approval.index', compact('records', 'departemen'));
    }

    public function show(RequestBarang $requestBarang)
    {
        $requestBarang->load(['departemen', 'gedung', 'peminta', 'approver', 'details.barang.kategori']);
        return view('transaksi.approval.show', compact('requestBarang'));
    }

    public function approve(RequestBarang $requestBarang)
    {
        if ($requestBarang->status !== 'Menunggu Approval') {
            return back()->with('error', 'Request ini sudah diproses.');
        }

        $this->approvalService->approve($requestBarang, auth()->id());
        return back()->with('success', 'Request berhasil disetujui.');
    }

    public function reject(Request $request, RequestBarang $requestBarang)
    {
        $request->validate(['alasan_reject' => 'required|string|min:10']);

        if ($requestBarang->status !== 'Menunggu Approval') {
            return back()->with('error', 'Request ini sudah diproses.');
        }

        $this->approvalService->reject($requestBarang, auth()->id(), $request->alasan_reject);
        return back()->with('success', 'Request telah ditolak.');
    }

    public function selesai(RequestBarang $requestBarang)
    {
        if ($requestBarang->status !== 'Diproses') {
            return back()->with('error', 'Request belum disetujui atau sudah selesai.');
        }

        $this->approvalService->selesai($requestBarang, auth()->id());
        return back()->with('success', 'Barang telah diserahkan. Status diubah menjadi Selesai.');
    }
}
