<x-app-layout>
    <x-slot name="title">Laporan Histori Nota</x-slot>

    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('laporan.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">No. Nota</th><th class="px-5 py-4">Waktu Cetak</th><th class="px-5 py-4">Dicetak Oleh</th><th class="px-5 py-4">Departemen Terkait</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($records as $item)
                    <tr class="hover:bg-gray-700/20">
                        <td class="px-5 py-3 font-mono text-cyan-400">{{ $item->no_transaksi }}</td>
                        <td class="px-5 py-3 text-gray-300">{{ \Carbon\Carbon::parse($item->waktu_cetak)->format('d/m/Y H:i:s') }}</td>
                        <td class="px-5 py-3 text-white font-medium">{{ optional($item->dicetakOleh)->nama_lengkap }}</td>
                        <td class="px-5 py-3 text-gray-400">{{ optional(optional($item->requestBarang)->departemen)->nama ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-gray-500"><p>Belum ada history nota.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>