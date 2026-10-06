const fs = require('fs');
const path = require('path');

const masterDataEntities = [
    { name: 'Kategori', folder: 'kategori', routePrefix: 'kategori', varName: 'kategori', fields: ['kode_prefix', 'nama'] },
    { name: 'Gedung', folder: 'gedung', routePrefix: 'gedung', varName: 'gedung', fields: ['kode', 'nama', 'lokasi', 'deskripsi'] },
    { name: 'Departemen', folder: 'departemen', routePrefix: 'departemen', varName: 'departemen', fields: ['kode', 'nama', 'deskripsi', 'kontak_pic'] },
    { name: 'Supplier', folder: 'supplier', routePrefix: 'supplier', varName: 'supplier', fields: ['kode', 'nama', 'kategori', 'kontak', 'pic', 'alamat'] },
    { name: 'Barang', folder: 'barang', routePrefix: 'barang', varName: 'barang', fields: ['kode_barang', 'nama', 'kategori_id', 'departemen_id', 'satuan', 'harga_satuan', 'minimum_stock'] }
];

const basePath = path.join(__dirname, 'resources', 'views', 'master-data');

if (!fs.existsSync(basePath)) {
    fs.mkdirSync(basePath, { recursive: true });
}

masterDataEntities.forEach(entity => {
    const dir = path.join(basePath, entity.folder);
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });

    // index.blade.php
    const indexContent = `<x-app-layout>
    <x-slot name="title">Master ${entity.name}</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white tracking-tight">Master ${entity.name}</h2>
        <a href="{{ route('master.${entity.routePrefix}.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4 inline-block mr-1"></i> Tambah ${entity.name}
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="p-4 border-b border-gray-700/50 flex flex-wrap gap-4 items-center justify-between">
            <div class="relative max-w-sm w-full">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" placeholder="Cari ${entity.name.toLowerCase()}..." class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        ${entity.fields.map(f => '<th class="px-6 py-4">' + f.replace('_', ' ') + '</th>').join('\n                        ')}
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse ($${entity.varName} as $index => $item)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-6 py-4 text-gray-400">{{ $index + 1 }}</td>
                        ${entity.fields.map(f => '<td class="px-6 py-4">{{ $item->' + f + ' }}</td>').join('\n                        ')}
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('master.${entity.routePrefix}.edit', $item->id) }}" class="p-2 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('master.${entity.routePrefix}.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:bg-rose-400/10 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="${entity.fields.length + 2}" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="inbox" class="w-12 h-12 mb-4 text-gray-500"></i>
                                <p>Belum ada data ${entity.name.toLowerCase()}.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">
            {{ $${entity.varName}->links() }}
        </div>
    </div>
</x-app-layout>`;

    fs.writeFileSync(path.join(dir, 'index.blade.php'), indexContent);

    // create.blade.php
    const createContent = `<x-app-layout>
    <x-slot name="title">Tambah ${entity.name}</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('master.${entity.routePrefix}.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-2xl font-bold text-white tracking-tight">Tambah ${entity.name}</h2>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
            <form action="{{ route('master.${entity.routePrefix}.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                ${entity.fields.map(f => 
                '<div>' +
                    '<label class="block text-sm font-medium text-gray-300 mb-2">' + f.replace('_', ' ').toUpperCase() + '</label>' +
                    '<input type="text" name="' + f + '" class="w-full bg-gray-900/50 border @error(\'' + f + '\') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" value="{{ old(\'' + f + '\') }}">' +
                    '@error(\'' + f + '\') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror' +
                '</div>').join('\n')}

                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <button type="reset" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Reset</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>`;

    fs.writeFileSync(path.join(dir, 'create.blade.php'), createContent);

    // edit.blade.php
    const editContent = `<x-app-layout>
    <x-slot name="title">Edit ${entity.name}</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('master.${entity.routePrefix}.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-2xl font-bold text-white tracking-tight">Edit ${entity.name}</h2>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
            <form action="{{ route('master.${entity.routePrefix}.update', $${entity.varName}->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                ${entity.fields.map(f => 
                '<div>' +
                    '<label class="block text-sm font-medium text-gray-300 mb-2">' + f.replace('_', ' ').toUpperCase() + '</label>' +
                    '<input type="text" name="' + f + '" class="w-full bg-gray-900/50 border @error(\'' + f + '\') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" value="{{ old(\'' + f + '\', $' + entity.varName + '->' + f + ') }}">' +
                    '@error(\'' + f + '\') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror' +
                '</div>').join('\n')}

                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>`;

    fs.writeFileSync(path.join(dir, 'edit.blade.php'), editContent);

    console.log("Generated views for " + entity.name);
});
