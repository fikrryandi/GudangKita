<x-app-layout>
    <x-slot name="title">Stock Adjustment</x-slot>

    <div class="flex justify-between items-center mb-6">
        <button @click="$dispatch('open-modal', 'create-adjustment')"
                class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4"></i> Adjustment Baru
        </button>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Jenis</th>
                        <th class="px-5 py-4">Barang</th>
                        <th class="px-5 py-4">Departemen</th>
                        <th class="px-5 py-4">Gedung</th>
                        <th class="px-5 py-4">Qty (-)</th>
                        <th class="px-5 py-4">Nilai Kerugian</th>
                        <th class="px-5 py-4">Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($records as $item)
                    @php
                        $jenisColors = ['rusak' => 'text-rose-400', 'hilang' => 'text-rose-500', 'kadaluarsa' => 'text-orange-400', 'selisih' => 'text-amber-400', 'koreksi input' => 'text-blue-400'];
                    @endphp
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 text-gray-300">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-5 py-3.5"><span class="capitalize font-medium {{ $jenisColors[$item->jenis] ?? 'text-gray-400' }}">{{ $item->jenis }}</span></td>
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-white">{{ optional($item->barang)->nama }}</p>
                            <p class="text-xs font-mono text-cyan-400">{{ optional($item->barang)->kode_barang }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->departemen)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->gedung)->nama }}</td>
                        <td class="px-5 py-3.5 font-bold text-rose-400">-{{ $item->qty }} {{ optional($item->barang)->satuan }}</td>
                        <td class="px-5 py-3.5 text-gray-300">Rp {{ number_format($item->nilai_kerugian, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-gray-400">{{ optional($item->user)->nama_lengkap }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada data adjustment.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $records->links() }}</div>
    </div>

    {{-- Modal Stock Adjustment --}}
    <x-modal name="create-adjustment" title="Buat Stock Adjustment Baru" max-width="3xl">
        <form action="{{ route('transaksi.adjustment.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Jenis Adjustment <span class="text-rose-400">*</span></label>
                    <select name="jenis" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="rusak">Rusak</option>
                        <option value="hilang">Hilang</option>
                        <option value="selisih">Selisih / Beda Data</option>
                        <option value="kadaluarsa">Kadaluarsa (Expired)</option>
                        <option value="koreksi input">Koreksi Input</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Departemen <span class="text-rose-400">*</span></label>
                    <select name="departemen_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach(\App\Models\Departemen::all() as $d)
                        <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Gedung / Lokasi <span class="text-rose-400">*</span></label>
                    <select name="gedung_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Pilih Gedung --</option>
                        @foreach(\App\Models\Gedung::all() as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Barang yang Disesuaikan <span class="text-rose-400">*</span></label>
                    <select name="barang_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Pilih Barang --</option>
                        @foreach(\App\Models\Barang::where('is_aktif', true)->get() as $b)
                        <option value="{{ $b->id }}">{{ $b->kode_barang }} - {{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah Dikurangi (Qty -) <span class="text-rose-400">*</span></label>
                    <input type="number" name="qty" value="1" min="1" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none text-rose-400 font-bold">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Alasan Detail <span class="text-rose-400">*</span></label>
                <textarea name="alasan" rows="3" placeholder="Contoh: Barang ditemukan rusak saat pengecekan mingguan..." class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
            </div>

            <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                <button type="button" @click="$dispatch('close-modal', 'create-adjustment')" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</button>
                <button type="submit" onclick="return confirm('Anda yakin akan melakukan pengurangan stok? Aksi ini akan dicatat ke dalam log kerugian.')" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(225,29,72,0.3)]">Proses Adjustment</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>