<x-app-layout>
    <x-slot name="title">Master Supplier</x-slot>

    <div class="flex justify-between items-center mb-6">
        <button @click="$dispatch('open-modal', 'create-supplier')"
                class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Supplier
        </button>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">Kode</th>
                        <th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Telepon</th>
                        <th class="px-5 py-4">Email</th>
                        <th class="px-5 py-4">PIC</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($supplier as $i => $item)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 text-gray-400">{{ $supplier->firstItem() + $i }}</td>
                        <td class="px-5 py-3.5 font-mono text-cyan-400">{{ $item->kode_supplier }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ $item->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->telepon ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->email ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->pic ?? '-' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 text-xs rounded-full {{ $item->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="$dispatch('open-modal', 'edit-supplier-{{ $item->id }}')"
                                        class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('master.supplier.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan supplier ini?')">
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
                        <td colspan="8" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada data supplier.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $supplier->links() }}</div>
    </div>

    {{-- Modal Tambah Supplier --}}
    <x-modal name="create-supplier" title="Tambah Supplier Baru" max-width="2xl">
        <form action="{{ route('master.supplier.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Supplier <span class="text-rose-400">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                           class="w-full bg-gray-800 border @error('nama') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('nama') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@supplier.com"
                           class="w-full bg-gray-800 border @error('email') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('email') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">PIC</label>
                    <input type="text" name="pic" value="{{ old('pic') }}"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('alamat') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3 justify-end pt-2 border-t border-gray-700/50">
                <button type="button" @click="$dispatch('close-modal', 'create-supplier')"
                        class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</button>
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan</button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Edit Supplier --}}
    @foreach($supplier as $item)
    <x-modal name="edit-supplier-{{ $item->id }}" title="Edit Supplier — {{ $item->nama }}" max-width="2xl">
        <form action="{{ route('master.supplier.update', $item->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Supplier <span class="text-rose-400">*</span></label>
                    <input type="text" name="nama" value="{{ $item->nama }}"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Telepon</label>
                    <input type="text" name="telepon" value="{{ $item->telepon }}"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ $item->email }}"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">PIC</label>
                    <input type="text" name="pic" value="{{ $item->pic }}"
                           class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ $item->alamat }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" {{ $item->status ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-600 text-indigo-600 focus:ring-indigo-500 bg-gray-800">
                        <span class="text-sm font-medium text-gray-300">Supplier Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 justify-end pt-2 border-t border-gray-700/50">
                <button type="button" @click="$dispatch('close-modal', 'edit-supplier-{{ $item->id }}')"
                        class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</button>
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Update</button>
            </div>
        </form>
    </x-modal>
    @endforeach
</x-app-layout>