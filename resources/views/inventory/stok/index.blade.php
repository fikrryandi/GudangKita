<x-app-layout>
    <x-slot name="title">Stok Barang</x-slot>

    <div class="flex justify-between items-center mb-6">
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-4 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]"><label class="block text-xs text-gray-400 mb-1">Cari Barang</label>
                <div class="relative"><i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"></div></div>
            <div class="min-w-[140px]"><label class="block text-xs text-gray-400 mb-1">Departemen</label>
                <select name="departemen_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua</option>
                    @foreach($departemen as $d)<option value="{{ $d->id }}" {{ request('departemen_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>@endforeach
                </select></div>
            <div class="min-w-[140px]"><label class="block text-xs text-gray-400 mb-1">Gedung</label>
                <select name="gedung_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua</option>
                    @foreach($gedung as $g)<option value="{{ $g->id }}" {{ request('gedung_id') == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>@endforeach
                </select></div>
            <div class="min-w-[120px]"><label class="block text-xs text-gray-400 mb-1">Status</label>
                <select name="status" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua Status</option>
                    <option value="aman" {{ request('status')=='aman' ? 'selected' : '' }}>Aman</option>
                    <option value="menipis" {{ request('status')=='menipis' ? 'selected' : '' }}>Menipis</option>
                    <option value="habis" {{ request('status')=='habis' ? 'selected' : '' }}>Habis</option>
                </select></div>
            <div class="flex gap-2"><button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm transition-colors">Cari</button>
                <a href="{{ route('inventory.stok.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors">Reset</a></div>
        </form>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama Barang</th><th class="px-5 py-4">Kategori</th><th class="px-5 py-4">Departemen</th><th class="px-5 py-4">Gedung</th><th class="px-5 py-4">Stok</th><th class="px-5 py-4">Min. Stok</th><th class="px-5 py-4">Nilai</th><th class="px-5 py-4">Status</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($stok as $item)
                    @php
                        $b = $item->barang;
                        $minStok = optional($b)->minimum_stock ?? 0;
                        $status = $item->qty == 0 ? 'habis' : ($item->qty <= $minStok ? 'menipis' : 'aman');
                        $statusClass = ['aman' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'menipis' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'habis' => 'bg-rose-500/10 text-rose-400 border-rose-500/20'][$status];
                        $statusLabel = ['aman' => 'Aman', 'menipis' => 'Menipis', 'habis' => 'Habis'][$status];
                    @endphp
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-cyan-400">{{ optional($b)->kode_barang }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ optional($b)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional(optional($b)->kategori)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->departemen)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->gedung)->nama }}</td>
                        <td class="px-5 py-3.5 font-bold {{ $item->qty == 0 ? 'text-rose-400' : ($item->qty <= $minStok ? 'text-amber-400' : 'text-white') }}">{{ $item->qty }} {{ optional($b)->satuan }}</td>
                        <td class="px-5 py-3.5 text-gray-400">{{ $minStok }}</td>
                        <td class="px-5 py-3.5 text-gray-300">Rp {{ number_format($item->qty * optional($b)->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5"><span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $statusClass }}">{{ $statusLabel }}</span></td>
                    </tr>
                                        @empty
                    <tr>
                        <td colspan="9" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada data stok.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $stok->links() }}</div>
    </div>


</x-app-layout>