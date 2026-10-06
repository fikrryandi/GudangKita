<x-app-layout>
    <x-slot name="title">Tambah Barang</x-slot>
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('master.barang.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl">
            <form action="{{ route('master.barang.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nama Barang <span class="text-rose-400">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="w-full bg-gray-900/50 border @error('nama') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        @error('nama') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Kategori <span class="text-rose-400">*</span></label>
                        <select name="kategori_id" class="w-full bg-gray-900/50 border @error('kategori_id') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }} ({{ $k->kode_prefix }})</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Departemen <span class="text-rose-400">*</span></label>
                        <select name="departemen_id" class="w-full bg-gray-900/50 border @error('departemen_id') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemen as $d)
                            <option value="{{ $d->id }}" {{ old('departemen_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                        @error('departemen_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Satuan <span class="text-rose-400">*</span></label>
                        <input type="text" name="satuan" value="{{ old('satuan') }}" placeholder="Pcs, Rim, Unit, Liter..." class="w-full bg-gray-900/50 border @error('satuan') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        @error('satuan') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Harga Satuan (Rp) <span class="text-rose-400">*</span></label>
                        <input type="number" name="harga_satuan" value="{{ old('harga_satuan') }}" min="0" step="100" class="w-full bg-gray-900/50 border @error('harga_satuan') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        @error('harga_satuan') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Minimum Stok <span class="text-rose-400">*</span></label>
                        <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" min="0" class="w-full bg-gray-900/50 border @error('minimum_stock') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        @error('minimum_stock') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all resize-none">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('master.barang.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>