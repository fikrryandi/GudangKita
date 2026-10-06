<x-app-layout>
    <x-slot name="title">Pratinjau Nota - {{ $nota->no_transaksi }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('transaksi.approval.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
            </div>
            <a href="{{ route('transaksi.nota.cetak', $requestBarang->id) }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)] flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak / Download PDF
            </a>
        </div>

        {{-- Kertas Nota (Mirip Cetakan) --}}
        <div class="bg-white rounded-lg shadow-2xl p-10 text-gray-800 mx-auto" style="max-width: 800px;">
            {{-- Header --}}
            <div class="flex justify-between items-start border-b-2 border-gray-300 pb-6 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">GUDANGKITA</h1>
                    <p class="text-sm text-gray-500 mt-1">Sistem Manajemen Inventory & Aset</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-bold text-gray-800 uppercase tracking-widest">NOTA PENGAMBILAN</h2>
                    <p class="text-sm font-mono mt-1 text-gray-600">{{ $nota->no_transaksi }}</p>
                    <p class="text-xs text-gray-500 mt-1">Dicetak: {{ \Carbon\Carbon::parse($nota->waktu_cetak)->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            {{-- Info --}}
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Informasi Request</p>
                    <p class="text-sm"><span class="inline-block w-24 text-gray-500">No. Request</span> : <span class="font-mono font-medium">{{ $requestBarang->no_transaksi }}</span></p>
                    <p class="text-sm"><span class="inline-block w-24 text-gray-500">Tanggal</span> : {{ \Carbon\Carbon::parse($requestBarang->tanggal)->format('d/m/Y') }}</p>
                    <p class="text-sm"><span class="inline-block w-24 text-gray-500">Keperluan</span> : {{ $requestBarang->keperluan }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Peminta</p>
                    <p class="text-sm"><span class="inline-block w-24 text-gray-500">Nama</span> : <span class="font-medium">{{ optional($requestBarang->peminta)->nama_lengkap }}</span></p>
                    <p class="text-sm"><span class="inline-block w-24 text-gray-500">Departemen</span> : {{ optional($requestBarang->departemen)->nama }}</p>
                    <p class="text-sm"><span class="inline-block w-24 text-gray-500">Lokasi</span> : {{ optional($requestBarang->gedung)->nama }}</p>
                </div>
            </div>

            {{-- Tabel Barang --}}
            <table class="w-full text-left text-sm mb-8">
                <thead class="border-b-2 border-gray-800">
                    <tr>
                        <th class="py-2 px-2">No</th>
                        <th class="py-2 px-2">Kode Barang</th>
                        <th class="py-2 px-2">Nama Barang</th>
                        <th class="py-2 px-2 text-center">Qty</th>
                        <th class="py-2 px-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($requestBarang->details as $i => $detail)
                    <tr>
                        <td class="py-3 px-2">{{ $i+1 }}</td>
                        <td class="py-3 px-2 font-mono text-gray-600">{{ optional($detail->barang)->kode_barang }}</td>
                        <td class="py-3 px-2 font-medium text-gray-900">{{ optional($detail->barang)->nama }}</td>
                        <td class="py-3 px-2 text-center font-bold">{{ $detail->qty }} {{ optional($detail->barang)->satuan }}</td>
                        <td class="py-3 px-2 text-gray-500">-</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- TTD --}}
            <div class="grid grid-cols-3 gap-4 text-center mt-12 text-sm pt-8">
                <div>
                    <p class="mb-16">Peminta / Penerima,</p>
                    <p class="font-bold underline">{{ optional($requestBarang->peminta)->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500">Tgl: .....................</p>
                </div>
                <div>
                    <p class="mb-16">Mengetahui (Admin),</p>
                    <p class="font-bold underline">{{ optional($requestBarang->approver)->nama_lengkap ?? '.........................' }}</p>
                    <p class="text-xs text-gray-500">Tgl: .....................</p>
                </div>
                <div>
                    <p class="mb-16">Petugas Gudang,</p>
                    <p class="font-bold underline">.........................</p>
                    <p class="text-xs text-gray-500">Tgl: .....................</p>
                </div>
            </div>
            
            <div class="mt-8 border-t border-dashed border-gray-300 pt-4 text-center text-xs text-gray-400">
                Dokumen ini dicetak dari sistem GudangKita secara otomatis. Harap simpan sebagai bukti transaksi yang sah.
            </div>
        </div>
    </div>
</x-app-layout>