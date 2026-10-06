<x-app-layout>
    <x-slot name="title">Kartu Stok</x-slot>

    <div class="flex justify-between items-center mb-6">
    </div>

    {{-- Filter --}}
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-6 p-5">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[250px]">
                <label class="block text-xs font-medium text-gray-400 mb-1">Pilih Barang <span class="text-rose-400">*</span></label>
                <select name="barang_id" required class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barang as $b)<option value="{{ $b->id }}" {{ request('barang_id') == $b->id ? 'selected' : '' }}>{{ $b->kode_barang }} - {{ $b->nama }} ({{ optional($b->departemen)->nama }})</option>@endforeach
                </select>
            </div>
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
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Tampilkan Kartu Stok</button>
            </div>
        </form>
    </div>

    @if(isset($movements) && isset($selected))
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden mb-6 p-5">
        <h3 class="font-semibold text-white border-b border-gray-700/50 pb-2 mb-4">Informasi Barang</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div><p class="text-gray-400 text-xs uppercase">Kode Barang</p><p class="text-white font-mono">{{ $selected['barang']->kode_barang }}</p></div>
            <div><p class="text-gray-400 text-xs uppercase">Nama Barang</p><p class="text-white font-medium">{{ $selected['barang']->nama }}</p></div>
            <div><p class="text-gray-400 text-xs uppercase">Kategori / Satuan</p><p class="text-white">{{ optional($selected['barang']->kategori)->nama }} / {{ $selected['barang']->satuan }}</p></div>
            <div><p class="text-gray-400 text-xs uppercase">Lokasi Filter</p><p class="text-white">{{ $selected['departemen']->nama }} / {{ $selected['gedung']->nama }}</p></div>
        </div>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">Waktu</th><th class="px-5 py-4">No Transaksi</th><th class="px-5 py-4">Tipe</th><th class="px-5 py-4 text-center">Masuk</th><th class="px-5 py-4 text-center">Keluar</th><th class="px-5 py-4 text-center">Saldo</th><th class="px-5 py-4">Oleh</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @php $saldo = 0; @endphp
                    @forelse($movements as $item)
                    @php
                        $masuk = in_array($item->jenis_transaksi, ['barang_masuk', 'transfer_in', 'return_in']);
                        $keluar = in_array($item->jenis_transaksi, ['barang_keluar', 'transfer_out', 'adjustment']);
                        $opname = $item->jenis_transaksi == 'opname';
                        
                        if ($masuk) $saldo += $item->qty;
                        if ($keluar) $saldo -= $item->qty;
                        if ($opname) {
                            $masuk = $item->qty > 0;
                            $keluar = $item->qty < 0;
                            $saldo += $item->qty;
                        }
                    @endphp
                    <tr class="hover:bg-gray-700/20">
                        <td class="px-5 py-3 text-gray-300">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}<br><span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</span></td>
                        <td class="px-5 py-3 font-mono text-cyan-400">{{ $item->no_transaksi ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-300 capitalize">{{ str_replace('_', ' ', $item->jenis_transaksi) }}</td>
                        <td class="px-5 py-3 text-center text-emerald-400 font-bold">{{ $masuk ? abs($item->qty) : '-' }}</td>
                        <td class="px-5 py-3 text-center text-rose-400 font-bold">{{ $keluar ? abs($item->qty) : '-' }}</td>
                        <td class="px-5 py-3 text-center text-white font-bold bg-gray-900/30">{{ $saldo }}</td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ optional($item->user)->nama_lengkap }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-gray-500"><p>Belum ada pergerakan stok untuk barang ini di lokasi terpilih.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $movements->links() }}</div>
    </div>
    @endif
</x-app-layout>