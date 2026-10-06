<x-app-layout>
    <x-slot name="title">Edit Kategori</x-slot>

    <div class="max-w-xl mx-auto">
                <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('master.kategori.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('master.kategori.update', $kategori->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kode Prefix</label>
                    <input type="text" name="kode_prefix" value="{{ old('kode_prefix', $kategori->kode_prefix) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none uppercase">
                    @error('kode_prefix') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Kategori</label>
                    <input type="text" name="nama" value="{{ old('nama', $kategori->nama) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('nama') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('master.kategori.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Update</button>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>