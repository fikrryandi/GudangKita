<x-app-layout>
    <x-slot name="title">Approval Request Barang</x-slot>

    <div class="flex justify-between items-center mb-6">
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-4 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]"><label class="block text-xs text-gray-400 mb-1">Cari No. Transaksi</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"></div>
            <div class="min-w-[160px]"><label class="block text-xs text-gray-400 mb-1">Status</label>
                <select name="status" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Approval" {{ request('status')=='Menunggu Approval' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="Diproses" {{ request('status')=='Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status')=='Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Ditolak" {{ request('status')=='Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select></div>
            <div class="min-w-[140px]"><label class="block text-xs text-gray-400 mb-1">Departemen</label>
                <select name="departemen_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua</option>
                    @foreach($departemen as $d)<option value="{{ $d->id }}" {{ request('departemen_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>@endforeach
                </select></div>
            <div class="flex gap-2"><button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm transition-colors">Cari</button>
                <a href="{{ route('transaksi.approval.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors">Reset</a></div>
        </form>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr>
                        <th class="px-5 py-4">No. Transaksi</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Peminta</th>
                        <th class="px-5 py-4">Departemen</th>
                        <th class="px-5 py-4">Keperluan</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($records as $item)
                    @php
                        $statusClass = ['Menunggu Approval' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'Diproses' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'Selesai' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'Ditolak' => 'bg-rose-500/10 text-rose-400 border-rose-500/20'][$item->status] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20';
                    @endphp
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-cyan-400 font-medium">{{ $item->no_transaksi }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->peminta)->nama_lengkap }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->departemen)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300 max-w-xs truncate">{{ $item->keperluan }}</td>
                        <td class="px-5 py-3.5"><span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $statusClass }}">{{ $item->status }}</span></td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('transaksi.approval.show', $item->id) }}" class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors inline-flex" title="Proses">
                                <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada request barang.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $records->links() }}</div>
    </div>
</x-app-layout>