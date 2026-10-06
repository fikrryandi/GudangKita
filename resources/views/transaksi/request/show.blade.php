<x-app-layout>
    <x-slot name="title">Detail Request - {{ $requestBarang->no_transaksi }}</x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('transaksi.request.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
            <div>
                <p class="text-sm font-mono text-cyan-400">{{ $requestBarang->no_transaksi }}</p>
            </div>
            @php
                $statusClass = ['Menunggu Approval' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'Diproses' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'Selesai' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'Ditolak' => 'bg-rose-500/10 text-rose-400 border-rose-500/20'][$requestBarang->status] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20';
            @endphp
            <span class="ml-auto px-3 py-1.5 text-sm font-medium rounded-full border {{ $statusClass }}">{{ $requestBarang->status }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-5">
                <h3 class="font-semibold text-white mb-4 pb-2 border-b border-gray-700/50">Informasi Permintaan</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-400">No. Transaksi</dt><dd class="text-white font-mono">{{ $requestBarang->no_transaksi }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Tanggal</dt><dd class="text-white">{{ \Carbon\Carbon::parse($requestBarang->tanggal)->format('d/m/Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Departemen</dt><dd class="text-white">{{ optional($requestBarang->departemen)->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Gedung</dt><dd class="text-white">{{ optional($requestBarang->gedung)->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Peminta</dt><dd class="text-white">{{ optional($requestBarang->peminta)->nama_lengkap }}</dd></div>
                    @if($requestBarang->approver)
                    <div class="flex justify-between"><dt class="text-gray-400">Disetujui oleh</dt><dd class="text-emerald-400">{{ optional($requestBarang->approver)->nama_lengkap }}</dd></div>
                    @endif
                    @if($requestBarang->alasan_reject)
                    <div class="flex justify-between"><dt class="text-gray-400">Alasan Ditolak</dt><dd class="text-rose-400">{{ $requestBarang->alasan_reject }}</dd></div>
                    @endif
                </dl>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-5">
                <h3 class="font-semibold text-white mb-4 pb-2 border-b border-gray-700/50">Keperluan & Catatan</h3>
                <p class="text-white font-medium mb-3">{{ $requestBarang->keperluan }}</p>
                <p class="text-gray-400 text-sm">{{ $requestBarang->catatan ?? 'Tidak ada catatan tambahan.' }}</p>
            </div>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
            <div class="p-5 border-b border-gray-700/50"><h3 class="font-semibold text-white">Daftar Barang Diminta</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                        <tr><th class="px-5 py-4">No</th><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama Barang</th><th class="px-5 py-4">Qty</th><th class="px-5 py-4">Satuan</th><th class="px-5 py-4">Harga Satuan</th><th class="px-5 py-4">Subtotal</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @php $total = 0; @endphp
                        @foreach($requestBarang->details as $i => $detail)
                        @php $total += $detail->subtotal; @endphp
                        <tr class="hover:bg-gray-700/20">
                            <td class="px-5 py-3.5 text-gray-400">{{ $i+1 }}</td>
                            <td class="px-5 py-3.5 font-mono text-cyan-400">{{ optional($detail->barang)->kode_barang }}</td>
                            <td class="px-5 py-3.5 text-white">{{ optional($detail->barang)->nama }}</td>
                            <td class="px-5 py-3.5 text-gray-300">{{ $detail->qty }}</td>
                            <td class="px-5 py-3.5 text-gray-300">{{ optional($detail->barang)->satuan }}</td>
                            <td class="px-5 py-3.5 text-gray-300">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-white">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-900/50">
                            <td colspan="6" class="px-5 py-3.5 text-right font-bold text-white">TOTAL NILAI</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-400">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
