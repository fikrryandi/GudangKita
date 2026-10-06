<x-app-layout>
    <x-slot name="title">Stock Opname</x-slot>

    <div class="flex justify-between items-center mb-6">
    </div>

    {{-- Form Filter untuk Memulai Opname --}}
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-6 p-5">
        <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4 border-b border-gray-700/50 pb-2">Mulai Stock Opname Baru</h3>
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[200px]">
                <label class="block text-xs font-medium text-gray-400 mb-1">Pilih Departemen <span class="text-rose-400">*</span></label>
                <select name="departemen_id" required class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Pilih --</option>
                    @foreach($departemen as $d)<option value="{{ $d->id }}" {{ request('departemen_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>@endforeach
                </select>
            </div>
            <div class="min-w-[200px]">
                <label class="block text-xs font-medium text-gray-400 mb-1">Pilih Gedung/Lokasi <span class="text-rose-400">*</span></label>
                <select name="gedung_id" required class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Pilih --</option>
                    @foreach($gedung as $g)<option value="{{ $g->id }}" {{ request('gedung_id') == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>@endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Tampilkan Data Barang</button>
            </div>
        </form>
    </div>

    {{-- Form Input Fisik (Hanya Muncul jika filter di atas terisi) --}}
    @if(isset($stokList))
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-8 overflow-hidden">
        <div class="p-5 border-b border-gray-700/50 bg-indigo-900/20">
            <h3 class="font-semibold text-indigo-400">Input Data Fisik Stock Opname</h3>
            <p class="text-xs text-gray-400 mt-1">Masukkan jumlah stok fisik sebenarnya yang ada di gudang.</p>
        </div>
        
        <form action="{{ route('inventory.stock-opname.store') }}" method="POST">
            @csrf
            <input type="hidden" name="departemen_id" value="{{ request('departemen_id') }}">
            <input type="hidden" name="gedung_id" value="{{ request('gedung_id') }}">
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                        <tr><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama Barang</th><th class="px-5 py-4 text-center">Stok Sistem (Qty)</th><th class="px-5 py-4">Stok Fisik (Input) <span class="text-rose-400">*</span></th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($stokList as $i => $item)
                        <tr class="hover:bg-gray-700/20">
                            <td class="px-5 py-3 font-mono text-cyan-400">{{ optional($item->barang)->kode_barang }}</td>
                            <td class="px-5 py-3 text-white">{{ optional($item->barang)->nama }}</td>
                            <td class="px-5 py-3 text-center text-gray-300 font-medium">{{ $item->qty }}</td>
                            <td class="px-5 py-3 w-48">
                                <input type="hidden" name="items[{{ $i }}][barang_id]" value="{{ $item->barang_id }}">
                                <input type="number" name="items[{{ $i }}][stok_fisik]" required value="{{ $item->qty }}" min="0" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-indigo-500 outline-none text-center font-bold">
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-12 text-center text-gray-500">Tidak ada stok barang di lokasi ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(count($stokList) > 0)
            <div class="p-5 bg-gray-900/30 flex justify-between items-center border-t border-gray-700/50">
                <div class="flex-1 mr-4">
                    <input type="text" name="keterangan" placeholder="Keterangan / Catatan Opname..." class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                </div>
                <button type="submit" onclick="return confirm('Simpan hasil stock opname?')" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)] whitespace-nowrap">
                    Simpan Hasil Opname
                </button>
            </div>
            @endif
        </form>
    </div>
    @endif

    {{-- Riwayat Stock Opname --}}
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="p-5 border-b border-gray-700/50">
            <h3 class="font-semibold text-white">Riwayat Stock Opname</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">Tanggal</th><th class="px-5 py-4">Departemen/Gedung</th><th class="px-5 py-4">Keterangan</th><th class="px-5 py-4">Oleh</th><th class="px-5 py-4">Status</th><th class="px-5 py-4 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($opname as $item)
                    <tr class="hover:bg-gray-700/20">
                        <td class="px-5 py-3 text-gray-300">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">
                            <p class="text-white">{{ optional($item->departemen)->nama }}</p>
                            <p class="text-xs text-gray-400">{{ optional($item->gedung)->nama }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-300">{{ $item->keterangan ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-400">{{ optional($item->user)->nama_lengkap }}</td>
                        <td class="px-5 py-3">
                            @if($item->status == 'Draft')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full border bg-amber-500/10 text-amber-400 border-amber-500/20">Draft (Belum Diterapkan)</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full border bg-emerald-500/10 text-emerald-400 border-emerald-500/20">Selesai (Diterapkan)</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if($item->status == 'Draft')
                            <form action="{{ route('inventory.stock-opname.apply', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Terapkan selisih opname ini ke stok sistem? Aksi ini tidak dapat dibatalkan.')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs rounded-lg transition-colors">Terapkan ke Stok</button>
                            </form>
                            @else
                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 inline-block mr-2"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-500"><p>Belum ada riwayat stock opname.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $opname->links() }}</div>
    </div>
</x-app-layout>