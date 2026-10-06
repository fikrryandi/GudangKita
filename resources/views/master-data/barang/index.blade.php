<x-app-layout>
    <x-slot name="title">Master Barang</x-slot>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-400 mt-1">Kelola semua data barang gudang.</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-barang')"
                class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Barang
        </button>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-6 p-4">
        <form method="GET" action="{{ route('master.barang.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-gray-400 mb-1">Cari</label>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau kode barang..." class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs text-gray-400 mb-1">Kategori</label>
                <select name="kategori_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs text-gray-400 mb-1">Departemen</label>
                <select name="departemen_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Semua Departemen</option>
                    @foreach($departemen as $dept)
                    <option value="{{ $dept->id }}" {{ request('departemen_id') == $dept->id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm transition-colors">Cari</button>
                <a href="{{ route('master.barang.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">Kode</th>
                        <th class="px-5 py-4">Nama Barang</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4">Departemen</th>
                        <th class="px-5 py-4">Satuan</th>
                        <th class="px-5 py-4">Harga Satuan</th>
                        <th class="px-5 py-4">Min. Stok</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($barang as $i => $item)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 text-gray-400">{{ $barang->firstItem() + $i }}</td>
                        <td class="px-5 py-3.5 font-mono text-cyan-400 font-medium">{{ $item->kode_barang }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ $item->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->kategori)->nama ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->departemen)->nama ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->satuan }}</td>
                        <td class="px-5 py-3.5 text-gray-300">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->minimum_stock }}</td>
                        <td class="px-5 py-3.5">
                            @if($item->is_aktif)
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="$dispatch('open-modal', 'edit-barang-{{ $item->id }}')"
                                        class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors" title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('master.barang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan barang ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-400 hover:bg-rose-400/10 rounded-lg transition-colors" title="Nonaktifkan">
                                        <i data-lucide="ban" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada data barang.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">
            {{ $barang->links() }}
        </div>
    </div>

    {{-- Modal Tambah Barang --}}
    <x-modal name="create-barang" title="Tambah Barang Baru" max-width="2xl">
        <form action="{{ route('master.barang.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Barang <span class="text-rose-400">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap barang"
                           class="w-full bg-gray-800 border @error('nama') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('nama') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kategori <span class="text-rose-400">*</span></label>
                    <select name="kategori_id" class="w-full bg-gray-800 border @error('kategori_id') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->kode_prefix }} - {{ $kat->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Departemen <span class="text-rose-400">*</span></label>
                    <select name="departemen_id" class="w-full bg-gray-800 border @error('departemen_id') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departemen as $dept)
                        <option value="{{ $dept->id }}" {{ old('departemen_id') == $dept->id ? 'selected' : '' }}>{{ $dept->kode }} - {{ $dept->nama }}</option>
                        @endforeach
                    </select>
                    @error('departemen_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Satuan <span class="text-rose-400">*</span></label>
                    <input type="text" name="satuan" value="{{ old('satuan') }}" placeholder="pcs, dus, liter, kg..."
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('satuan') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Harga Satuan <span class="text-rose-400">*</span></label>
                    <input type="number" name="harga_satuan" value="{{ old('harga_satuan', 0) }}" min="0"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('harga_satuan') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Minimum Stok <span class="text-rose-400">*</span></label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" min="0"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('minimum_stock') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" placeholder="Keterangan tambahan barang (opsional)..."
                              class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3 justify-end pt-2 border-t border-gray-700/50">
                <button type="button" @click="$dispatch('close-modal', 'create-barang')"
                        class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</button>
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan Barang</button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Edit Barang (satu per item) --}}
    @foreach($barang as $item)
    <x-modal name="edit-barang-{{ $item->id }}" title="Edit Barang — {{ $item->kode_barang }}" max-width="2xl">
        <form action="{{ route('master.barang.update', $item->id) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Barang <span class="text-rose-400">*</span></label>
                    <input type="text" name="nama" value="{{ $item->nama }}"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kategori <span class="text-rose-400">*</span></label>
                    <select name="kategori_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ $item->kategori_id == $kat->id ? 'selected' : '' }}>{{ $kat->kode_prefix }} - {{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Departemen <span class="text-rose-400">*</span></label>
                    <select name="departemen_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @foreach($departemen as $dept)
                        <option value="{{ $dept->id }}" {{ $item->departemen_id == $dept->id ? 'selected' : '' }}>{{ $dept->kode }} - {{ $dept->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Satuan <span class="text-rose-400">*</span></label>
                    <input type="text" name="satuan" value="{{ $item->satuan }}"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Harga Satuan <span class="text-rose-400">*</span></label>
                    <input type="number" name="harga_satuan" value="{{ $item->harga_satuan }}" min="0"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Minimum Stok</label>
                    <input type="number" name="minimum_stock" value="{{ $item->minimum_stock }}" min="0"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="2"
                              class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ $item->deskripsi }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_aktif" value="0">
                        <input type="checkbox" name="is_aktif" value="1" {{ $item->is_aktif ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-600 text-indigo-600 focus:ring-indigo-500 bg-gray-800">
                        <span class="text-sm font-medium text-gray-300">Barang Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 justify-end pt-2 border-t border-gray-700/50">
                <button type="button" @click="$dispatch('close-modal', 'edit-barang-{{ $item->id }}')"
                        class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</button>
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Update</button>
            </div>
        </form>
    </x-modal>
    @endforeach
</x-app-layout>