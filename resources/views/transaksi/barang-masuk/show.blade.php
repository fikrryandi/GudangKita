<x-app-layout>
    <x-slot name="title">Detail Barang Masuk - {{ $barangMasuk->no_transaksi }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('transaksi.barang-masuk.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
            <div>
                <p class="text-sm font-mono text-cyan-400">{{ $barangMasuk->no_transaksi }}</p>
            </div>
            <span class="ml-auto px-3 py-1.5 text-sm font-medium rounded-full border {{ $barangMasuk->kondisi == 'Baik' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20' }}">{{ $barangMasuk->kondisi }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-5">
                <h3 class="font-semibold text-white mb-4 pb-2 border-b border-gray-700/50">Informasi Transaksi</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-400">No. Transaksi</dt><dd class="text-white font-mono">{{ $barangMasuk->no_transaksi }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Tanggal</dt><dd class="text-white">{{ \Carbon\Carbon::parse($barangMasuk->tanggal)->format('d/m/Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Diinput Oleh</dt><dd class="text-white">{{ optional($barangMasuk->user)->nama_lengkap }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Waktu Input</dt><dd class="text-white">{{ \Carbon\Carbon::parse($barangMasuk->created_at)->format('H:i') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">No. Surat Jalan</dt><dd class="text-white">{{ $barangMasuk->no_surat_jalan ?? '-' }}</dd></div>
                </dl>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-5">
                <h3 class="font-semibold text-white mb-4 pb-2 border-b border-gray-700/50">Informasi Lokasi & Supplier</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-400">Departemen</dt><dd class="text-white">{{ optional($barangMasuk->departemen)->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Gedung/Lokasi</dt><dd class="text-white">{{ optional($barangMasuk->gedung)->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Supplier</dt><dd class="text-white">{{ optional($barangMasuk->supplier)->nama ?? 'Internal / Tanpa Supplier' }}</dd></div>
                </dl>
                @if($barangMasuk->keterangan)
                <div class="mt-4 pt-4 border-t border-gray-700/50">
                    <p class="text-xs text-gray-400 mb-1">Keterangan:</p>
                    <p class="text-white text-sm">{{ $barangMasuk->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
            <div class="p-5 border-b border-gray-700/50"><h3 class="font-semibold text-white">Rincian Barang Masuk</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                        <tr><th class="px-5 py-4">No</th><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama Barang</th><th class="px-5 py-4">Kategori</th><th class="px-5 py-4 text-center">Qty</th><th class="px-5 py-4">Harga Satuan</th><th class="px-5 py-4">Subtotal</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @php $grandTotal = 0; @endphp
                        @foreach($barangMasuk->details as $i => $detail)
                        @php $grandTotal += $detail->total_nilai; @endphp
                        <tr class="hover:bg-gray-700/20">
                            <td class="px-5 py-3.5 text-gray-400">{{ $i+1 }}</td>
                            <td class="px-5 py-3.5 font-mono text-cyan-400">{{ optional($detail->barang)->kode_barang }}</td>
                            <td class="px-5 py-3.5 text-white">{{ optional($detail->barang)->nama }}</td>
                            <td class="px-5 py-3.5 text-gray-400">{{ optional(optional($detail->barang)->kategori)->nama }}</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-400 text-center">{{ $detail->qty }} {{ optional($detail->barang)->satuan }}</td>
                            <td class="px-5 py-3.5 text-gray-300">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-white">Rp {{ number_format($detail->total_nilai, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-900/50">
                            <td colspan="6" class="px-5 py-3.5 text-right font-bold text-white uppercase tracking-wider text-xs">Total Nilai Masuk</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-400">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>