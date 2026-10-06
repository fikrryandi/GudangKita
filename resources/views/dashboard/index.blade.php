<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- Floating Low-Stock Notification Panel (Top Right, below navbar) --}}
    @if(collect($stokMenipis)->count() > 0 || collect($stokHabis)->count() > 0)
    <div x-data="{ open: true }"
         x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
         class="fixed top-20 right-5 z-50 w-80 rounded-2xl border shadow-2xl overflow-hidden"
         style="background: rgba(10, 17, 40, 0.97); backdrop-filter: blur(16px); border-color: rgba(220,38,38,0.4); box-shadow: 0 0 30px rgba(220,38,38,0.2);">

        <div class="flex items-center justify-between px-4 py-3 border-b border-red-500/20 bg-red-500/10">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </span>
                <span class="text-sm font-bold text-red-400">Peringatan Stok!</span>
            </div>
            <button @click="open = false" class="text-gray-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="max-h-60 overflow-y-auto scrollbar-thin divide-y divide-white/5 p-1">
            @foreach(collect($stokHabis)->take(3) as $stok)
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center flex-shrink-0 shadow-[0_0_10px_rgba(220,38,38,0.4)]">
                    <i data-lucide="x-circle" class="w-4 h-4 text-red-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ optional($stok->barang)->nama }}</p>
                    <p class="text-xs text-red-400 font-medium">Stok HABIS</p>
                </div>
                <span class="text-xs font-bold text-red-400 bg-red-500/10 border border-red-500/30 rounded-lg px-2 py-0.5">0</span>
            </div>
            @endforeach

            @foreach(collect($stokMenipis)->take(5) as $stok)
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center flex-shrink-0 shadow-[0_0_10px_rgba(245,158,11,0.4)]">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ optional($stok->barang)->nama }}</p>
                    <p class="text-xs text-amber-400 font-medium">Stok Menipis</p>
                </div>
                <span class="text-xs font-bold text-amber-400 bg-amber-500/10 border border-amber-500/30 rounded-lg px-2 py-0.5">{{ $stok->qty }}</span>
            </div>
            @endforeach
        </div>

        @if(collect($stokMenipis)->count() + collect($stokHabis)->count() > 8)
        <div class="px-4 py-2 border-t border-white/5 text-center">
            <a href="{{ route('inventory.stok.index', ['status' => 'menipis']) }}" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">
                Lihat Semua ({{ collect($stokMenipis)->count() + collect($stokHabis)->count() }} item)
            </a>
        </div>
        @endif
    </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-white tracking-tight" style="text-shadow: 0 0 15px rgba(255,255,255,0.3);">Dashboard</h2>
        <p class="text-blue-200 mt-1">Selamat datang di sistem manajemen inventory. Berikut adalah ringkasan data terbaru.</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-[#111827]/80 backdrop-blur-xl border border-blue-500/20 shadow-[0_0_20px_rgba(37,99,235,0.1)] rounded-2xl p-4 mb-8">
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-4 items-center w-full">
            <div class="flex-1 min-w-[180px]">
                <div class="relative">
                    <i data-lucide="building-2" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-400"></i>
                    <select name="departemen_id" class="w-full bg-[#0a1128] border border-blue-500/30 text-white rounded-xl pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                        <option value="">Semua Departemen</option>
                        @foreach($departemenQuery ?? [] as $dept)
                            <option value="{{ $dept->id }}" {{ $filterDept == $dept->id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex-1 min-w-[180px]">
                <div class="relative">
                    <i data-lucide="warehouse" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-400"></i>
                    <select name="gedung_id" class="w-full bg-[#0a1128] border border-blue-500/30 text-white rounded-xl pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                        <option value="">Semua Gedung</option>
                        @foreach(\App\Models\Gedung::all() as $g)
                            <option value="{{ $g->id }}" {{ $filterGedung == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex-1 min-w-[180px]">
                <div class="relative">
                    <i data-lucide="calendar" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-400"></i>
                    <select name="periode" class="w-full bg-[#0a1128] border border-blue-500/30 text-white rounded-xl pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                        <option value="minggu_ini" {{ $filterPeriode == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan_ini" {{ $filterPeriode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="bulan_lalu" {{ $filterPeriode == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="tahun_ini" {{ $filterPeriode == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl transition-all font-medium text-white shadow-[0_0_15px_rgba(37,99,235,0.4)] flex items-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i> Terapkan
            </button>
            <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-red-600 hover:bg-red-500 rounded-xl transition-all text-white shadow-[0_0_15px_rgba(220,38,38,0.4)]" title="Reset Filter">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            </a>
        </form>
    </div>

    <!-- Statistik Cards (5 cards — no Stok Menipis/Habis) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">

        {{-- Card 1: Total Barang — Blue --}}
        <div class="relative group rounded-2xl overflow-hidden p-5 transition-all duration-300 hover:-translate-y-1 cursor-default"
             style="background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 100%); border: 1px solid rgba(59,130,246,0.4); box-shadow: 0 0 25px rgba(37,99,235,0.25);">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                 style="background: radial-gradient(circle at 50% 0%, rgba(255,255,255,0.06) 0%, transparent 70%)"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: rgba(59,130,246,0.25); box-shadow: 0 0 15px rgba(59,130,246,0.5);">
                <i data-lucide="package" class="w-5 h-5 text-blue-200"></i>
            </div>
            <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider mb-1">Total Barang</p>
            <p class="text-3xl font-bold text-white tabular-nums">{{ number_format($totalBarang, 0, ',', '.') }}</p>
        </div>

        {{-- Card 2: Total Stok — Indigo --}}
        <div class="relative group rounded-2xl overflow-hidden p-5 transition-all duration-300 hover:-translate-y-1 cursor-default"
             style="background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%); border: 1px solid rgba(99,102,241,0.4); box-shadow: 0 0 25px rgba(79,70,229,0.25);">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                 style="background: radial-gradient(circle at 50% 0%, rgba(255,255,255,0.06) 0%, transparent 70%)"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: rgba(99,102,241,0.25); box-shadow: 0 0 15px rgba(99,102,241,0.5);">
                <i data-lucide="layers" class="w-5 h-5 text-indigo-200"></i>
            </div>
            <p class="text-xs font-semibold text-indigo-200 uppercase tracking-wider mb-1">Total Stok</p>
            <p class="text-3xl font-bold text-white tabular-nums">{{ number_format($totalStok, 0, ',', '.') }}</p>
        </div>

        {{-- Card 3: Total Nilai — Cyan/Teal --}}
        <div class="relative group rounded-2xl overflow-hidden p-5 transition-all duration-300 hover:-translate-y-1 cursor-default"
             style="background: linear-gradient(135deg, #134e4a 0%, #0e7490 100%); border: 1px solid rgba(6,182,212,0.4); box-shadow: 0 0 25px rgba(6,182,212,0.2);">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                 style="background: radial-gradient(circle at 50% 0%, rgba(255,255,255,0.06) 0%, transparent 70%)"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: rgba(6,182,212,0.25); box-shadow: 0 0 15px rgba(6,182,212,0.5);">
                <i data-lucide="wallet" class="w-5 h-5 text-cyan-200"></i>
            </div>
            <p class="text-xs font-semibold text-cyan-200 uppercase tracking-wider mb-1">Total Nilai (Rp)</p>
            <p class="text-xl font-bold text-white tabular-nums leading-tight">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
        </div>

        {{-- Card 4: Barang Masuk — Emerald/Green --}}
        <div class="relative group rounded-2xl overflow-hidden p-5 transition-all duration-300 hover:-translate-y-1 cursor-default"
             style="background: linear-gradient(135deg, #14532d 0%, #047857 100%); border: 1px solid rgba(16,185,129,0.4); box-shadow: 0 0 25px rgba(16,185,129,0.2);">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                 style="background: radial-gradient(circle at 50% 0%, rgba(255,255,255,0.06) 0%, transparent 70%)"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: rgba(16,185,129,0.25); box-shadow: 0 0 15px rgba(16,185,129,0.5);">
                <i data-lucide="arrow-down-to-line" class="w-5 h-5 text-emerald-200"></i>
            </div>
            <p class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-1">Barang Masuk</p>
            <p class="text-3xl font-bold text-white tabular-nums">{{ number_format($barangMasukCount, 0, ',', '.') }}</p>
        </div>

        {{-- Card 5: Barang Keluar — Red/Rose --}}
        <div class="relative group rounded-2xl overflow-hidden p-5 transition-all duration-300 hover:-translate-y-1 cursor-default"
             style="background: linear-gradient(135deg, #4c0519 0%, #be123c 100%); border: 1px solid rgba(244,63,94,0.4); box-shadow: 0 0 25px rgba(220,38,38,0.2);">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                 style="background: radial-gradient(circle at 50% 0%, rgba(255,255,255,0.06) 0%, transparent 70%)"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: rgba(244,63,94,0.25); box-shadow: 0 0 15px rgba(244,63,94,0.5);">
                <i data-lucide="arrow-up-from-line" class="w-5 h-5 text-rose-200"></i>
            </div>
            <p class="text-xs font-semibold text-rose-200 uppercase tracking-wider mb-1">Barang Keluar</p>
            <p class="text-3xl font-bold text-white tabular-nums">{{ number_format($barangKeluarCount, 0, ',', '.') }}</p>
        </div>

    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

        <!-- Line Chart -->
        <div class="lg:col-span-6 bg-[#111827]/80 backdrop-blur-xl border border-blue-500/20 rounded-2xl p-5 shadow-[0_0_20px_rgba(37,99,235,0.05)] flex flex-col">
            <h3 class="text-md font-bold text-white mb-4 flex items-center gap-2"><i data-lucide="trending-up" class="w-5 h-5 text-blue-400"></i> Grafik Barang Masuk dan Keluar</h3>
            <div class="flex-1 relative min-h-[250px]">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart -->
        <div class="lg:col-span-3 bg-[#111827]/80 backdrop-blur-xl border border-blue-500/20 rounded-2xl p-5 shadow-[0_0_20px_rgba(37,99,235,0.05)] flex flex-col">
            <h3 class="text-md font-bold text-white mb-4 flex items-center gap-2"><i data-lucide="pie-chart" class="w-5 h-5 text-blue-400"></i> Stok per Departemen</h3>
            <div class="flex-1 relative min-h-[250px] flex items-center justify-center">
                <canvas id="deptChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pb-2">
                    <span class="text-xs text-blue-200">Total Stok</span>
                    <span class="text-lg font-bold text-white">{{ number_format($totalStok, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="lg:col-span-3 bg-[#111827]/80 backdrop-blur-xl border border-blue-500/20 rounded-2xl p-5 shadow-[0_0_20px_rgba(37,99,235,0.05)] flex flex-col">
            <h3 class="text-md font-bold text-white mb-4 flex items-center gap-2"><i data-lucide="bar-chart-2" class="w-5 h-5 text-blue-400"></i> Stok per Gedung</h3>
            <div class="flex-1 relative min-h-[250px]">
                <canvas id="gedungChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Daftar Stok Menipis -->
        <div class="bg-[#111827]/80 backdrop-blur-xl border border-red-500/20 rounded-2xl shadow-[0_0_20px_rgba(220,38,38,0.05)] overflow-hidden flex flex-col h-[400px]">
            <div class="p-5 border-b border-white/5 flex justify-between items-center bg-white/5">
                <h3 class="text-md font-bold text-white flex items-center gap-2"><i data-lucide="alert-triangle" class="w-5 h-5 text-red-400"></i> Daftar Stok Menipis</h3>
                <a href="{{ route('inventory.stok.index', ['status' => 'menipis']) }}" class="text-xs text-blue-400 hover:text-blue-300">Lihat Semua</a>
            </div>
            <div class="flex-1 overflow-auto scrollbar-thin">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-xs text-blue-200 uppercase bg-[#0a1128]/50 sticky top-0 z-10 border-b border-white/5">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Barang</th>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3 text-right">Stok</th>
                            <th class="px-5 py-3 text-right">Min</th>
                            <th class="px-5 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($stokMenipis->take(10) as $i => $stok)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-5 py-3 text-gray-400">{{ $i+1 }}</td>
                            <td class="px-5 py-3 font-medium text-white">{{ optional($stok->barang)->nama }}</td>
                            <td class="px-5 py-3 font-mono text-blue-300">{{ optional($stok->barang)->kode_barang }}</td>
                            <td class="px-5 py-3 text-right font-bold {{ $stok->qty == 0 ? 'text-red-400' : 'text-amber-400' }}">{{ $stok->qty }}</td>
                            <td class="px-5 py-3 text-right text-gray-400">{{ optional($stok->barang)->minimum_stock }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($stok->qty == 0)
                                <span class="px-2 py-1 text-[10px] uppercase font-bold rounded bg-red-500/20 text-red-400 border border-red-500/30">Habis</span>
                                @else
                                <span class="px-2 py-1 text-[10px] uppercase font-bold rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">Menipis</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-gray-500">Semua stok aman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="bg-[#111827]/80 backdrop-blur-xl border border-blue-500/20 rounded-2xl shadow-[0_0_20px_rgba(37,99,235,0.05)] overflow-hidden flex flex-col h-[400px]">
            <div class="p-5 border-b border-white/5 flex justify-between items-center bg-white/5">
                <h3 class="text-md font-bold text-white flex items-center gap-2"><i data-lucide="clock" class="w-5 h-5 text-blue-400"></i> Transaksi Terbaru</h3>
                <a href="#" class="text-xs text-blue-400 hover:text-blue-300">Lihat Semua</a>
            </div>
            <div class="flex-1 overflow-auto scrollbar-thin">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-xs text-blue-200 uppercase bg-[#0a1128]/50 sticky top-0 z-10 border-b border-white/5">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="px-5 py-3">Barang</th>
                            <th class="px-5 py-3 text-right">Jumlah</th>
                            <th class="px-5 py-3">User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($transaksiTerbaru as $i => $trx)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-5 py-3 text-gray-400">{{ $i+1 }}</td>
                            <td class="px-5 py-3 text-gray-300">{{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3">
                                @if($trx->jenis == 'masuk')
                                    <span class="text-emerald-400 font-medium">Barang Masuk</span>
                                @else
                                    <span class="text-red-400 font-medium">Barang Keluar</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-medium text-white">{{ optional($trx->barang)->nama }}</td>
                            <td class="px-5 py-3 text-right font-bold">
                                @if($trx->jenis == 'masuk')
                                    <span class="text-emerald-400">+{{ $trx->qty_masuk }}</span>
                                @else
                                    <span class="text-red-400">-{{ $trx->qty_keluar }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-400">{{ optional($trx->user)->nama_lengkap ?? 'Sistem' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-gray-500">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.color = '#9CA3AF';
            Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
            Chart.defaults.font.family = "'Inter', sans-serif";

            // 1. Line Chart (Activity)
            const ctxActivity = document.getElementById('activityChart').getContext('2d');
            let gradMasuk = ctxActivity.createLinearGradient(0, 0, 0, 400);
            gradMasuk.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
            gradMasuk.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            let gradKeluar = ctxActivity.createLinearGradient(0, 0, 0, 400);
            gradKeluar.addColorStop(0, 'rgba(220, 38, 38, 0.4)');
            gradKeluar.addColorStop(1, 'rgba(220, 38, 38, 0.0)');

            new Chart(ctxActivity, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartDays) !!},
                    datasets: [
                        {
                            label: 'Barang Masuk',
                            data: {!! json_encode($chartMasuk) !!},
                            borderColor: '#3B82F6',
                            backgroundColor: gradMasuk,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#fff',
                            pointRadius: 3,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'Barang Keluar',
                            data: {!! json_encode($chartKeluar) !!},
                            borderColor: '#DC2626',
                            backgroundColor: gradKeluar,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#DC2626',
                            pointBorderColor: '#fff',
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8 } },
                        tooltip: { backgroundColor: 'rgba(15,23,42,0.9)', titleColor: '#fff', bodyColor: '#D1D5DB', borderColor: 'rgba(59,130,246,0.3)', borderWidth: 1 }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, border: { display: false } }
                    }
                }
            });

            // 2. Doughnut Chart (Stok per Departemen)
            const deptDataRaw = {!! json_encode($stokPerDepartemen) !!};
            const deptLabels = deptDataRaw.map(d => d.nama);
            const deptValues = deptDataRaw.map(d => d.total_qty);
            const chartColors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#06B6D4', '#F97316'];

            const ctxDept = document.getElementById('deptChart').getContext('2d');
            new Chart(ctxDept, {
                type: 'doughnut',
                data: {
                    labels: deptLabels,
                    datasets: [{
                        data: deptValues,
                        backgroundColor: chartColors.slice(0, Math.max(deptLabels.length, 1)),
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8, padding: 12, font: { size: 11 } } },
                        tooltip: { backgroundColor: 'rgba(15,23,42,0.9)', titleColor: '#fff', bodyColor: '#D1D5DB', borderColor: 'rgba(59,130,246,0.3)', borderWidth: 1 }
                    }
                }
            });

            // 3. Bar Chart (Stok per Gedung)
            const gedungDataRaw = {!! json_encode($stokPerGedung) !!};
            const gedungLabels = gedungDataRaw.map(d => d.nama);
            const gedungValues = gedungDataRaw.map(d => d.total_qty);

            const ctxGedung = document.getElementById('gedungChart').getContext('2d');
            new Chart(ctxGedung, {
                type: 'bar',
                data: {
                    labels: gedungLabels,
                    datasets: [{
                        label: 'Stok',
                        data: gedungValues,
                        backgroundColor: chartColors.slice(0, Math.max(gedungLabels.length, 1)),
                        borderRadius: 6,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: 'rgba(15,23,42,0.9)', titleColor: '#fff', bodyColor: '#D1D5DB', borderColor: 'rgba(59,130,246,0.3)', borderWidth: 1 }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, border: { display: false } }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
