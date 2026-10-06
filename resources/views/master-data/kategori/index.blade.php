<x-app-layout>
    <x-slot name="title">Master Kategori</x-slot>

    <div class="flex justify-between items-center mb-6">
        <button @click="$dispatch('open-modal', 'create-kategori')"
                class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori
        </button>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">Kode Prefix</th>
                        <th class="px-5 py-4">Nama Kategori</th>
                        <th class="px-5 py-4">Jumlah Barang</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($kategori as $i => $item)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 text-gray-400">{{ $kategori->firstItem() + $i }}</td>
                        <td class="px-5 py-3.5 font-mono font-bold text-cyan-400">{{ $item->kode_prefix }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ $item->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->barang_count ?? 0 }} Barang</td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="$dispatch('open-modal', 'edit-kategori-{{ $item->id }}')"
                                        class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('master.kategori.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-400 hover:bg-rose-400/10 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada kategori barang.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $kategori->links() }}</div>
    </div>

    {{-- Modal Tambah Kategori --}}
    <x-modal name="create-kategori" title="Tambah Kategori Baru" max-width="md">
        <form action="{{ route('master.kategori.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Kode Prefix <span class="text-rose-400">*</span> <span class="text-gray-500 font-normal">(cth: ATK, APD, SPR)</span></label>
                <input type="text" name="kode_prefix" value="{{ old('kode_prefix') }}"
                       class="w-full bg-gray-800 border @error('kode_prefix') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none uppercase"
                       placeholder="ATK" style="text-transform:uppercase" autofocus>
                @error('kode_prefix') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Kategori <span class="text-rose-400">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       class="w-full bg-gray-800 border @error('nama') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"
                       placeholder="Alat Tulis Kantor">
                @error('nama') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="flex gap-3 justify-end pt-2 border-t border-gray-700/50">
                <button type="button" @click="$dispatch('close-modal', 'create-kategori')"
                        class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</button>
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan</button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Edit Kategori (satu per item) --}}
    @foreach($kategori as $item)
    <x-modal name="edit-kategori-{{ $item->id }}" title="Edit Kategori" max-width="md">
        <form action="{{ route('master.kategori.update', $item->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Kode Prefix <span class="text-rose-400">*</span></label>
                <input type="text" name="kode_prefix" value="{{ $item->kode_prefix }}"
                       class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none uppercase"
                       style="text-transform:uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Kategori <span class="text-rose-400">*</span></label>
                <input type="text" name="nama" value="{{ $item->nama }}"
                       class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="flex gap-3 justify-end pt-2 border-t border-gray-700/50">
                <button type="button" @click="$dispatch('close-modal', 'edit-kategori-{{ $item->id }}')"
                        class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</button>
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Update</button>
            </div>
        </form>
    </x-modal>
    @endforeach

    @if(session('_old_input') || $errors->any())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-kategori' }));
        });
    </script>
    @endpush
    @endif
</x-app-layout>