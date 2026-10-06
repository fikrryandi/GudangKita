<x-app-layout>
    <x-slot name="title">Proses Approval - {{ $requestBarang->no_transaksi }}</x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('transaksi.approval.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
            <div>
                <p class="text-sm font-mono text-cyan-400">{{ $requestBarang->no_transaksi }}</p>
            </div>
            @php $statusClass = ['Menunggu Approval' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'Diproses' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'Selesai' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'Ditolak' => 'bg-rose-500/10 text-rose-400 border-rose-500/20'][$requestBarang->status] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20'; @endphp
            <span class="ml-auto px-3 py-1.5 text-sm font-medium rounded-full border {{ $statusClass }}">{{ $requestBarang->status }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-5">
                <h3 class="font-semibold text-white mb-4 pb-2 border-b border-gray-700/50">Informasi Permintaan</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-400">No. Transaksi</dt><dd class="text-white font-mono">{{ $requestBarang->no_transaksi }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Tanggal</dt><dd class="text-white">{{ \Carbon\Carbon::parse($requestBarang->tanggal)->format('d/m/Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Peminta</dt><dd class="text-white">{{ optional($requestBarang->peminta)->nama_lengkap }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Departemen</dt><dd class="text-white">{{ optional($requestBarang->departemen)->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Gedung</dt><dd class="text-white">{{ optional($requestBarang->gedung)->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Keperluan</dt><dd class="text-white">{{ $requestBarang->keperluan }}</dd></div>
                    @if($requestBarang->alasan_reject)
                    <div class="flex justify-between"><dt class="text-gray-400">Alasan Tolak</dt><dd class="text-rose-400">{{ $requestBarang->alasan_reject }}</dd></div>
                    @endif
                </dl>
            </div>
            {{-- Action Panel --}}
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-5 space-y-4">
                <h3 class="font-semibold text-white pb-2 border-b border-gray-700/50">Tindakan</h3>
                @if($requestBarang->status === 'Menunggu Approval')
                <form action="{{ route('transaksi.approval.approve', $requestBarang->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Setujui request ini?')" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Setujui Request
                    </button>
                </form>
                <div x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="w-full px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> Tolak Request
                    </button>
                    <div x-show="open" x-transition class="mt-3">
                        <form action="{{ route('transaksi.approval.reject', $requestBarang->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <textarea name="alasan_reject" rows="3" placeholder="Masukkan alasan penolakan (minimal 10 karakter)..." class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-rose-500 outline-none resize-none text-sm"></textarea>
                            @error('alasan_reject') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                            <button type="submit" class="w-full px-4 py-2.5 bg-rose-700 hover:bg-rose-600 text-white rounded-xl font-medium transition-all">Konfirmasi Penolakan</button>
                        </form>
                    </div>
                </div>
                @elseif($requestBarang->status === 'Diproses')
                <form action="{{ route('transaksi.approval.selesai', $requestBarang->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Tandai request ini sebagai selesai?')" class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                        <i data-lucide="package-check" class="w-4 h-4"></i> Tandai Selesai (Barang Diserahkan)
                    </button>
                </form>
                @else
                <div class="text-center py-6 text-gray-500">
                    <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-gray-600"></i>
                    <p>Request ini sudah diproses ({{ $requestBarang->status }}).</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
            <div class="p-5 border-b border-gray-700/50"><h3 class="font-semibold text-white">Daftar Barang Diminta</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                        <tr><th class="px-5 py-4">No</th><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama Barang</th><th class="px-5 py-4">Kategori</th><th class="px-5 py-4">Qty</th><th class="px-5 py-4">Satuan</th><th class="px-5 py-4">Subtotal</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @php $total = 0; @endphp
                        @foreach($requestBarang->details as $i => $detail)
                        @php $total += $detail->subtotal; @endphp
                        <tr class="hover:bg-gray-700/20">
                            <td class="px-5 py-3.5 text-gray-400">{{ $i+1 }}</td>
                            <td class="px-5 py-3.5 font-mono text-cyan-400">{{ optional($detail->barang)->kode_barang }}</td>
                            <td class="px-5 py-3.5 text-white">{{ optional($detail->barang)->nama }}</td>
                            <td class="px-5 py-3.5 text-gray-400">{{ optional(optional($detail->barang)->kategori)->nama }}</td>
                            <td class="px-5 py-3.5 text-gray-300">{{ $detail->qty }}</td>
                            <td class="px-5 py-3.5 text-gray-300">{{ optional($detail->barang)->satuan }}</td>
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