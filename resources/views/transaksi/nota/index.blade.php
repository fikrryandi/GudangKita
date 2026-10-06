<x-app-layout>
    <x-slot name="title">Nota Pengambilan Barang</x-slot>

    <div class="flex justify-between items-center mb-6">
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr>
                        <th class="px-5 py-4">No. Nota</th>
                        <th class="px-5 py-4">No. Request</th>
                        <th class="px-5 py-4">Tanggal Dicetak</th>
                        <th class="px-5 py-4">Departemen</th>
                        <th class="px-5 py-4">Dicetak Oleh</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($records as $item)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-cyan-400 font-medium">{{ $item->no_transaksi }}</td>
                        <td class="px-5 py-3.5 font-mono text-gray-400">{{ optional($item->requestBarang)->no_transaksi }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ \Carbon\Carbon::parse($item->waktu_cetak)->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional(optional($item->requestBarang)->departemen)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-400">{{ optional($item->dicetakOleh)->nama_lengkap }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('transaksi.nota.show', $item->request_barang_id) }}" class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors inline-flex" title="Lihat">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('transaksi.nota.cetak', $item->request_barang_id) }}" class="p-1.5 text-emerald-400 hover:bg-emerald-400/10 rounded-lg transition-colors inline-flex" title="Cetak PDF">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada nota yang dicetak.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $records->links() }}</div>
    </div>
</x-app-layout>