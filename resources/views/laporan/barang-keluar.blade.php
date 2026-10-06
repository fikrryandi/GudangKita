<x-app-layout>
    <x-slot name="title">Laporan Barang Keluar</x-slot>

    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('laporan.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-6 p-5">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-400 mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-400 mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-colors shadow-lg shadow-indigo-500/20"><i data-lucide="filter" class="w-4 h-4 inline-block mr-1"></i> Filter</button>
                <button type="submit" name="export_pdf" value="1" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm font-medium transition-colors shadow-lg shadow-emerald-500/20"><i data-lucide="file-text" class="w-4 h-4 inline-block mr-1"></i> PDF</button>
            </div>
        </form>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">No. Transaksi</th><th class="px-5 py-4">Tanggal</th><th class="px-5 py-4">Peminta</th><th class="px-5 py-4">Departemen/Gedung</th><th class="px-5 py-4">Keperluan</th><th class="px-5 py-4">Status</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($records as $item)
                    <tr class="hover:bg-gray-700/20">
                        <td class="px-5 py-3 font-mono text-cyan-400">{{ $item->no_transaksi }}</td>
                        <td class="px-5 py-3 text-gray-300">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 font-medium text-white">{{ optional($item->peminta)->nama_lengkap }}</td>
                        <td class="px-5 py-3">
                            <p class="text-white">{{ optional($item->departemen)->nama }}</p>
                            <p class="text-xs text-gray-400">{{ optional($item->gedung)->nama }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-300 max-w-xs truncate">{{ $item->keperluan }}</td>
                        <td class="px-5 py-3 text-emerald-400">{{ $item->status }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-500"><p>Belum ada data laporan.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>