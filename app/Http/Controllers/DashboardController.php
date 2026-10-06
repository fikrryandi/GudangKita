<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Stok;
use App\Models\BarangMasuk;
use App\Models\RequestBarang;
use App\Models\StockMovement;
use App\Models\Departemen;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        // Filter departemen berdasarkan role
        $departemenQuery = $isSuperAdmin
            ? Departemen::all()
            : Departemen::where('id', $user->departemen_id)->get();

        $departemenIds = $departemenQuery->pluck('id');

        // Filter dari request (untuk Super Admin)
        $filterDept = $request->get('departemen_id');
        $filterGedung = $request->get('gedung_id');
        $filterPeriode = $request->get('periode', 'bulan_ini');

        $stokQuery = Stok::whereIn('departemen_id', $departemenIds);
        if ($filterDept) $stokQuery->where('departemen_id', $filterDept);
        if ($filterGedung) $stokQuery->where('gedung_id', $filterGedung);

        // Tanggal filter
        [$tglAwal, $tglAkhir] = $this->getPeriodeRange($filterPeriode);

        // Stat cards
        $totalBarang    = Barang::whereIn('departemen_id', $departemenIds)->count();
        $totalStok      = (clone $stokQuery)->sum('qty');
        $totalNilai     = Stok::with('barang')->whereIn('departemen_id', $departemenIds)
            ->get()->sum(fn($s) => $s->qty * optional($s->barang)->harga_satuan);

        $barangMasukCount = BarangMasuk::whereIn('departemen_id', $departemenIds)
            ->whereBetween('tanggal', [$tglAwal, $tglAkhir])->count();

        $barangKeluarCount = RequestBarang::whereIn('departemen_id', $departemenIds)
            ->whereIn('status', ['Diproses', 'Selesai'])
            ->whereBetween('tanggal', [$tglAwal, $tglAkhir])->count();

        // Stok menipis & habis
        $stokAll = Stok::with('barang')->whereIn('departemen_id', $departemenIds)->get();
        $stokMenipis = $stokAll->filter(fn($s) => $s->qty > 0 && $s->qty <= optional($s->barang)->minimum_stock)->values();
        $stokHabis   = $stokAll->filter(fn($s) => $s->qty === 0)->values();

        // Chart data: movement per hari (30 hari terakhir)
        $movements = StockMovement::whereIn('departemen_id', $departemenIds)
            ->where('tanggal', '>=', now()->subDays(29)->toDateString())
            ->selectRaw('DATE(tanggal) as tgl, jenis, SUM(qty_masuk) as masuk, SUM(qty_keluar) as keluar')
            ->groupBy('tgl', 'jenis')
            ->orderBy('tgl')
            ->get();

        $chartDays = collect();
        for ($i = 29; $i >= 0; $i--) {
            $chartDays->push(now()->subDays($i)->format('d/m'));
        }

        $chartMasuk  = $chartDays->map(fn($d) => 0)->values()->toArray();
        $chartKeluar = $chartDays->map(fn($d) => 0)->values()->toArray();

        foreach ($movements as $mov) {
            $dayLabel = \Carbon\Carbon::parse($mov->tgl)->format('d/m');
            $idx = $chartDays->search($dayLabel);
            if ($idx !== false) {
                $chartMasuk[$idx]  += $mov->masuk;
                $chartKeluar[$idx] += $mov->keluar;
            }
        }

        // Transaksi terbaru
        $transaksiTerbaru = StockMovement::with(['barang', 'departemen', 'gedung', 'user'])
            ->whereIn('departemen_id', $departemenIds)
            ->latest()
            ->take(5)
            ->get();

        // Data for Doughnut Chart: Stok per Departemen
        $stokPerDepartemen = DB::table('stok')
            ->join('departemen', 'stok.departemen_id', '=', 'departemen.id')
            ->whereIn('stok.departemen_id', $departemenIds)
            ->select('departemen.nama', DB::raw('SUM(stok.qty) as total_qty'))
            ->groupBy('departemen.nama')
            ->get();

        // Data for Bar Chart: Stok per Gedung
        $stokPerGedung = DB::table('stok')
            ->join('gedung', 'stok.gedung_id', '=', 'gedung.id')
            ->whereIn('stok.departemen_id', $departemenIds)
            ->select('gedung.nama', DB::raw('SUM(stok.qty) as total_qty'))
            ->groupBy('gedung.nama')
            ->get();

        return view('dashboard.index', compact(
            'totalBarang', 'totalStok', 'totalNilai',
            'barangMasukCount', 'barangKeluarCount',
            'stokMenipis', 'stokHabis',
            'chartDays', 'chartMasuk', 'chartKeluar',
            'transaksiTerbaru', 'stokPerDepartemen', 'stokPerGedung',
            'departemenQuery',
            'filterDept', 'filterGedung', 'filterPeriode'
        ));
    }

    private function getPeriodeRange(string $periode): array
    {
        return match ($periode) {
            'minggu_ini'  => [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
            'bulan_lalu'  => [now()->subMonth()->startOfMonth()->toDateString(), now()->subMonth()->endOfMonth()->toDateString()],
            'tahun_ini'   => [now()->startOfYear()->toDateString(), now()->toDateString()],
            default       => [now()->startOfMonth()->toDateString(), now()->toDateString()], // bulan_ini
        };
    }
}
