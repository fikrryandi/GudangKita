<x-app-layout>
    <x-slot name="title">Stock Adjustment Baru</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('transaksi.adjustment.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl" x-data="{ departemenId: '' }">
            <form action="{{ route('transaksi.adjustment.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Jenis Adjustment <span class="text-rose-400">*</span></label>
                        <select name="jenis" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="rusak" {{ old('jenis') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="hilang" {{ old('jenis') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            <option value="selisih" {{ old('jenis') == 'selisih' ? 'selected' : '' }}>Selisih / Beda Data</option>
                            <option value="kadaluarsa" {{ old('jenis') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa (Expired)</option>
                            <option value="koreksi input" {{ old('jenis') == 'koreksi input' ? 'selected' : '' }}>Koreksi Input</option>
                        </select>
                        @error('jenis') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Departemen <span class="text-rose-400">*</span></label>
                        <select name="departemen_id" x-model="departemenId" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemen as $d)<option value="{{ $d->id }}">{{ $d->nama }}</option>@endforeach
                        </select>
                        @error('departemen_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Gedung / Lokasi <span class="text-rose-400">*</span></label>
                        <select name="gedung_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Gedung --</option>
                            @foreach($gedung as $g)<option value="{{ $g->id }}">{{ $g->nama }}</option>@endforeach
                        </select>
                        @error('gedung_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Barang yang Disesuaikan <span class="text-rose-400">*</span></label>
                        <select name="barang_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none disabled:opacity-50">
                            <option value="">-- Pilih Barang --</option>
                            {{-- Ideally filled by AJAX based on departemen, using empty template for now --}}
                        </select>
                        @error('barang_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah Dikurangi (Qty -) <span class="text-rose-400">*</span></label>
                        <input type="number" name="qty" value="{{ old('qty', 1) }}" min="1" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none text-rose-400 font-bold">
                        @error('qty') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Alasan Detail <span class="text-rose-400">*</span></label>
                    <textarea name="alasan" rows="3" placeholder="Contoh: Barang ditemukan rusak saat pengecekan mingguan, atau Salah input qty pada barang masuk BM-001..." class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('alasan') }}</textarea>
                    @error('alasan') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('transaksi.adjustment.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" onclick="return confirm('Anda yakin akan melakukan pengurangan stok? Aksi ini akan dicatat ke dalam log kerugian.')" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(225,29,72,0.3)]">Proses Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>