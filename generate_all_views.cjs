const fs = require('fs');
const path = require('path');

const BASE = path.join(__dirname, 'resources', 'views');

function write(relPath, content) {
    const fullPath = path.join(BASE, relPath);
    fs.mkdirSync(path.dirname(fullPath), { recursive: true });
    fs.writeFileSync(fullPath, content);
    console.log('Written: ' + relPath);
}

const layout = (title, content, scripts = '') => `<x-app-layout>
    <x-slot name="title">${title}</x-slot>
${content}
${scripts ? '@push(\'scripts\')\n' + scripts + '\n@endpush' : ''}
</x-app-layout>`;

const backBtn = (route) => `        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('${route}') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
        </div>`;

const card = (content) => `        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
${content}
        </div>`;

const emptyTable = (cols, msg) => `                    @empty
                    <tr>
                        <td colspan="${cols}" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>${msg}</p>
                        </td>
                    </tr>`;

// =============================================
// MASTER DATA - KATEGORI
// =============================================
write('master-data/kategori/index.blade.php', layout('Master Kategori', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Master Kategori Barang</h2>
        <a href="{{ route('master.kategori.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori
        </a>
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
                                <a href="{{ route('master.kategori.edit', $item->id) }}" class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                <form action="{{ route('master.kategori.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-400 hover:bg-rose-400/10 rounded-lg transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    ${emptyTable(5, 'Belum ada kategori barang.')}
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $kategori->links() }}</div>
    </div>
`));

write('master-data/kategori/create.blade.php', layout('Tambah Kategori', `
    <div class="max-w-xl mx-auto">
        ${backBtn('master.kategori.index')}
        <h2 class="text-2xl font-bold text-white mb-6">Tambah Kategori</h2>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('master.kategori.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kode Prefix <span class="text-rose-400">*</span><span class="text-gray-500 font-normal ml-1">(contoh: ATK, APD, SPR)</span></label>
                    <input type="text" name="kode_prefix" value="{{ old('kode_prefix') }}" class="w-full bg-gray-900/50 border @error('kode_prefix') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none uppercase" placeholder="ATK">
                    @error('kode_prefix') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Kategori <span class="text-rose-400">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="w-full bg-gray-900/50 border @error('nama') border-rose-500 @else border-gray-700 @enderror text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('nama') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('master.kategori.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
`));

write('master-data/kategori/edit.blade.php', layout('Edit Kategori', `
    <div class="max-w-xl mx-auto">
        ${backBtn('master.kategori.index')}
        <h2 class="text-2xl font-bold text-white mb-6">Edit Kategori</h2>
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
`));

// =============================================
// MASTER DATA - SUPPLIER
// =============================================
write('master-data/supplier/index.blade.php', layout('Master Supplier', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Master Supplier</h2>
        <a href="{{ route('master.supplier.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Supplier</a>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr>
                        <th class="px-5 py-4">No</th><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Kontak</th><th class="px-5 py-4">PIC</th>
                        <th class="px-5 py-4">Status</th><th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($supplier as $i => $item)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 text-gray-400">{{ $supplier->firstItem() + $i }}</td>
                        <td class="px-5 py-3.5 font-mono text-cyan-400">{{ $item->kode }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ $item->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->kontak }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->pic }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 text-xs rounded-full {{ $item->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $item->status ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('master.supplier.edit', $item->id) }}" class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                <form action="{{ route('master.supplier.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-400 hover:bg-rose-400/10 rounded-lg transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    ${emptyTable(7, 'Belum ada data supplier.')}
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $supplier->links() }}</div>
    </div>
`));

write('master-data/supplier/create.blade.php', layout('Tambah Supplier', `
    <div class="max-w-2xl mx-auto">
        ${backBtn('master.supplier.index')}
        <h2 class="text-2xl font-bold text-white mb-6">Tambah Supplier</h2>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('master.supplier.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Nama Supplier *</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('nama') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                        <input type="text" name="kategori" value="{{ old('kategori') }}" placeholder="Alat Tulis, Safety, dll." class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Kontak (Telepon/Email)</label>
                        <input type="text" name="kontak" value="{{ old('kontak') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">PIC (Person In Charge)</label>
                        <input type="text" name="pic" value="{{ old('pic') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('alamat') }}</textarea></div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('master.supplier.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
`));

write('master-data/supplier/edit.blade.php', layout('Edit Supplier', `
    <div class="max-w-2xl mx-auto">
        ${backBtn('master.supplier.index')}
        <h2 class="text-2xl font-bold text-white mb-6">Edit Supplier</h2>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('master.supplier.update', $supplier->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Nama Supplier *</label>
                        <input type="text" name="nama" value="{{ old('nama', $supplier->nama) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                        <input type="text" name="kategori" value="{{ old('kategori', $supplier->kategori) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Kontak</label>
                        <input type="text" name="kontak" value="{{ old('kontak', $supplier->kontak) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">PIC</label>
                        <input type="text" name="pic" value="{{ old('pic', $supplier->pic) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('alamat', $supplier->alamat) }}</textarea></div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" {{ old('status', $supplier->status) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-600 text-indigo-600 focus:ring-indigo-500 bg-gray-900">
                        <span class="text-sm font-medium text-gray-300">Supplier Aktif</span>
                    </label>
                </div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('master.supplier.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Update</button>
                </div>
            </form>
        </div>
    </div>
`));

// =============================================
// MASTER DATA - DEPARTEMEN
// =============================================
write('master-data/departemen/index.blade.php', layout('Master Departemen', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Master Departemen</h2>
        <a href="{{ route('master.departemen.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Departemen</a>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">No</th><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama</th><th class="px-5 py-4">Deskripsi</th><th class="px-5 py-4">Kontak PIC</th><th class="px-5 py-4 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($departemen as $i => $item)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3.5 font-mono text-cyan-400 font-bold">{{ $item->kode }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ $item->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-400 max-w-xs truncate">{{ $item->deskripsi ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->kontak_pic ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('master.departemen.edit', $item->id) }}" class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                            </div>
                        </td>
                    </tr>
                    ${emptyTable(6, 'Belum ada data departemen.')}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
`));

write('master-data/departemen/create.blade.php', layout('Tambah Departemen', `
    <div class="max-w-xl mx-auto">
        ${backBtn('master.departemen.index')}
        <h2 class="text-2xl font-bold text-white mb-6">Tambah Departemen</h2>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('master.departemen.store') }}" method="POST" class="space-y-5">
                @csrf
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Kode *</label>
                    <input type="text" name="kode" value="{{ old('kode') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="HRGA, EHS, MTC">
                    @error('kode') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Nama Departemen *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('nama') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('deskripsi') }}</textarea></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Kontak PIC</label>
                    <input type="text" name="kontak_pic" value="{{ old('kontak_pic') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('master.departemen.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
`));

write('master-data/departemen/edit.blade.php', layout('Edit Departemen', `
    <div class="max-w-xl mx-auto">
        ${backBtn('master.departemen.index')}
        <h2 class="text-2xl font-bold text-white mb-6">Edit Departemen</h2>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('master.departemen.update', $departemen->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Kode</label>
                    <input type="text" name="kode" value="{{ old('kode', $departemen->kode) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Nama Departemen</label>
                    <input type="text" name="nama" value="{{ old('nama', $departemen->nama) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('deskripsi', $departemen->deskripsi) }}</textarea></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Kontak PIC</label>
                    <input type="text" name="kontak_pic" value="{{ old('kontak_pic', $departemen->kontak_pic) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('master.departemen.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Update</button>
                </div>
            </form>
        </div>
    </div>
`));

// =============================================
// MASTER DATA - GEDUNG
// =============================================
write('master-data/gedung/index.blade.php', layout('Master Gedung', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Master Gedung</h2>
        <a href="{{ route('master.gedung.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Gedung</a>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">No</th><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama Gedung</th><th class="px-5 py-4">Lokasi</th><th class="px-5 py-4">Deskripsi</th><th class="px-5 py-4 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($gedung as $i => $item)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3.5 font-mono text-cyan-400 font-bold">{{ $item->kode }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ $item->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $item->lokasi ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-400 max-w-xs truncate">{{ $item->deskripsi ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('master.gedung.edit', $item->id) }}" class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                <form action="{{ route('master.gedung.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-400 hover:bg-rose-400/10 rounded-lg transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    ${emptyTable(6, 'Belum ada data gedung.')}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
`));

write('master-data/gedung/create.blade.php', layout('Tambah Gedung', `
    <div class="max-w-xl mx-auto">
        ${backBtn('master.gedung.index')}
        <h2 class="text-2xl font-bold text-white mb-6">Tambah Gedung</h2>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('master.gedung.store') }}" method="POST" class="space-y-5">
                @csrf
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Kode *</label>
                    <input type="text" name="kode" value="{{ old('kode') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="GDG-001">
                    @error('kode') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Nama Gedung *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('nama') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('deskripsi') }}</textarea></div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('master.gedung.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
`));

write('master-data/gedung/edit.blade.php', layout('Edit Gedung', `
    <div class="max-w-xl mx-auto">
        ${backBtn('master.gedung.index')}
        <h2 class="text-2xl font-bold text-white mb-6">Edit Gedung</h2>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('master.gedung.update', $gedung->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Kode</label>
                    <input type="text" name="kode" value="{{ old('kode', $gedung->kode) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Nama Gedung</label>
                    <input type="text" name="nama" value="{{ old('nama', $gedung->nama) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $gedung->lokasi) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('deskripsi', $gedung->deskripsi) }}</textarea></div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('master.gedung.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Update</button>
                </div>
            </form>
        </div>
    </div>
`));

// =============================================
// TRANSAKSI - BARANG MASUK
// =============================================
write('transaksi/barang-masuk/index.blade.php', layout('Barang Masuk', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Barang Masuk</h2>
        <a href="{{ route('transaksi.barang-masuk.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]"><i data-lucide="plus" class="w-4 h-4"></i> Catat Barang Masuk</a>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-4 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]"><label class="block text-xs text-gray-400 mb-1">Cari No. Transaksi</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"></div>
            <div class="min-w-[140px]"><label class="block text-xs text-gray-400 mb-1">Departemen</label>
                <select name="departemen_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua</option>
                    @foreach($departemen as $d)<option value="{{ $d->id }}" {{ request('departemen_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>@endforeach
                </select></div>
            <div class="min-w-[130px]"><label class="block text-xs text-gray-400 mb-1">Dari</label><input type="date" name="dari" value="{{ request('dari') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none"></div>
            <div class="min-w-[130px]"><label class="block text-xs text-gray-400 mb-1">Sampai</label><input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none"></div>
            <div class="flex gap-2"><button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm transition-colors">Cari</button>
                <a href="{{ route('transaksi.barang-masuk.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors">Reset</a></div>
        </form>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">No. Transaksi</th><th class="px-5 py-4">Tanggal</th><th class="px-5 py-4">Departemen</th><th class="px-5 py-4">Gedung</th><th class="px-5 py-4">Supplier</th><th class="px-5 py-4">Kondisi</th><th class="px-5 py-4">Diinput oleh</th><th class="px-5 py-4 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($records as $item)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-cyan-400 font-medium">{{ $item->no_transaksi }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->departemen)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->gedung)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->supplier)->nama ?? '-' }}</td>
                        <td class="px-5 py-3.5"><span class="px-2 py-1 text-xs rounded-full {{ $item->kondisi == 'Baik' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">{{ $item->kondisi }}</span></td>
                        <td class="px-5 py-3.5 text-gray-400">{{ optional($item->user)->nama_lengkap }}</td>
                        <td class="px-5 py-3.5 text-right"><a href="{{ route('transaksi.barang-masuk.show', $item->id) }}" class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors inline-flex"><i data-lucide="eye" class="w-4 h-4"></i></a></td>
                    </tr>
                    ${emptyTable(8, 'Belum ada data barang masuk.')}
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $records->links() }}</div>
    </div>
`));

write('transaksi/barang-masuk/show.blade.php', layout('Detail Barang Masuk', `
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('transaksi.barang-masuk.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
            <div>
                <h2 class="text-2xl font-bold text-white">Detail Barang Masuk</h2>
                <p class="text-sm font-mono text-cyan-400">{{ $barangMasuk->no_transaksi }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-5">
                <h3 class="font-semibold text-white mb-4 pb-2 border-b border-gray-700/50">Informasi Transaksi</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-400">No. Transaksi</dt><dd class="text-white font-mono">{{ $barangMasuk->no_transaksi }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Tanggal</dt><dd class="text-white">{{ \Carbon\Carbon::parse($barangMasuk->tanggal)->format('d/m/Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Departemen</dt><dd class="text-white">{{ optional($barangMasuk->departemen)->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Gedung</dt><dd class="text-white">{{ optional($barangMasuk->gedung)->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Kondisi</dt><dd class="{{ $barangMasuk->kondisi == 'Baik' ? 'text-emerald-400' : 'text-rose-400' }}">{{ $barangMasuk->kondisi }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Supplier</dt><dd class="text-white">{{ optional($barangMasuk->supplier)->nama ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">No. Surat Jalan</dt><dd class="text-white">{{ $barangMasuk->no_surat_jalan ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-400">Diinput oleh</dt><dd class="text-white">{{ optional($barangMasuk->user)->nama_lengkap }}</dd></div>
                </dl>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-5">
                <h3 class="font-semibold text-white mb-4 pb-2 border-b border-gray-700/50">Keterangan</h3>
                <p class="text-gray-300 text-sm">{{ $barangMasuk->keterangan ?? 'Tidak ada keterangan.' }}</p>
            </div>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
            <div class="p-5 border-b border-gray-700/50"><h3 class="font-semibold text-white">Detail Barang</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                        <tr><th class="px-5 py-4">No</th><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama Barang</th><th class="px-5 py-4">Qty</th><th class="px-5 py-4">Satuan</th><th class="px-5 py-4">Harga</th><th class="px-5 py-4">Total</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @php $grandTotal = 0; @endphp
                        @foreach($barangMasuk->details as $i => $detail)
                        @php $grandTotal += $detail->total_nilai; @endphp
                        <tr class="hover:bg-gray-700/20">
                            <td class="px-5 py-3.5 text-gray-400">{{ $i+1 }}</td>
                            <td class="px-5 py-3.5 font-mono text-cyan-400">{{ optional($detail->barang)->kode_barang }}</td>
                            <td class="px-5 py-3.5 text-white">{{ optional($detail->barang)->nama }}</td>
                            <td class="px-5 py-3.5 text-gray-300">{{ $detail->qty }}</td>
                            <td class="px-5 py-3.5 text-gray-300">{{ optional($detail->barang)->satuan }}</td>
                            <td class="px-5 py-3.5 text-gray-300">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-white font-medium">Rp {{ number_format($detail->total_nilai, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-900/50">
                            <td colspan="6" class="px-5 py-3.5 text-right font-bold text-white">TOTAL</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-400">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
`));

// =============================================
// INVENTORY - STOK
// =============================================
write('inventory/stok/index.blade.php', layout('Stok Barang', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Stok Barang</h2>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-4 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]"><label class="block text-xs text-gray-400 mb-1">Cari Barang</label>
                <div class="relative"><i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"></div></div>
            <div class="min-w-[140px]"><label class="block text-xs text-gray-400 mb-1">Departemen</label>
                <select name="departemen_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua</option>
                    @foreach($departemen as $d)<option value="{{ $d->id }}" {{ request('departemen_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>@endforeach
                </select></div>
            <div class="min-w-[140px]"><label class="block text-xs text-gray-400 mb-1">Gedung</label>
                <select name="gedung_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua</option>
                    @foreach($gedung as $g)<option value="{{ $g->id }}" {{ request('gedung_id') == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>@endforeach
                </select></div>
            <div class="min-w-[120px]"><label class="block text-xs text-gray-400 mb-1">Status</label>
                <select name="status" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua Status</option>
                    <option value="aman" {{ request('status')=='aman' ? 'selected' : '' }}>Aman</option>
                    <option value="menipis" {{ request('status')=='menipis' ? 'selected' : '' }}>Menipis</option>
                    <option value="habis" {{ request('status')=='habis' ? 'selected' : '' }}>Habis</option>
                </select></div>
            <div class="flex gap-2"><button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm transition-colors">Cari</button>
                <a href="{{ route('inventory.stok.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors">Reset</a></div>
        </form>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama Barang</th><th class="px-5 py-4">Kategori</th><th class="px-5 py-4">Departemen</th><th class="px-5 py-4">Gedung</th><th class="px-5 py-4">Stok</th><th class="px-5 py-4">Min. Stok</th><th class="px-5 py-4">Nilai</th><th class="px-5 py-4">Status</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($stok as $item)
                    @php
                        $b = $item->barang;
                        $minStok = optional($b)->minimum_stock ?? 0;
                        $status = $item->qty == 0 ? 'habis' : ($item->qty <= $minStok ? 'menipis' : 'aman');
                        $statusClass = ['aman' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'menipis' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'habis' => 'bg-rose-500/10 text-rose-400 border-rose-500/20'][$status];
                        $statusLabel = ['aman' => 'Aman', 'menipis' => 'Menipis', 'habis' => 'Habis'][$status];
                    @endphp
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-cyan-400">{{ optional($b)->kode_barang }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ optional($b)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional(optional($b)->kategori)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->departemen)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->gedung)->nama }}</td>
                        <td class="px-5 py-3.5 font-bold {{ $item->qty == 0 ? 'text-rose-400' : ($item->qty <= $minStok ? 'text-amber-400' : 'text-white') }}">{{ $item->qty }} {{ optional($b)->satuan }}</td>
                        <td class="px-5 py-3.5 text-gray-400">{{ $minStok }}</td>
                        <td class="px-5 py-3.5 text-gray-300">Rp {{ number_format($item->qty * optional($b)->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5"><span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $statusClass }}">{{ $statusLabel }}</span></td>
                    </tr>
                    ${emptyTable(9, 'Belum ada data stok.')}
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $stok->links() }}</div>
    </div>
`));

// =============================================
// USER MANAGEMENT
// =============================================
write('user-management/user/index.blade.php', layout('Manajemen Pengguna', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Manajemen Pengguna</h2>
        <a href="{{ route('user-management.user.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]"><i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Pengguna</a>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl mb-4 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]"><label class="block text-xs text-gray-400 mb-1">Cari</label>
                <div class="relative"><i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau username..." class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"></div></div>
            <div class="min-w-[150px]"><label class="block text-xs text-gray-400 mb-1">Role</label>
                <select name="role" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2 text-sm outline-none">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)<option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>@endforeach
                </select></div>
            <div class="flex gap-2"><button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm transition-colors">Cari</button>
                <a href="{{ route('user-management.user.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors">Reset</a></div>
        </form>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">Pengguna</th><th class="px-5 py-4">Username</th><th class="px-5 py-4">Email</th><th class="px-5 py-4">Role</th><th class="px-5 py-4">Departemen</th><th class="px-5 py-4">Status</th><th class="px-5 py-4 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($users as $user)
                    @php $roleColors = ['Super Admin' => 'bg-rose-500/10 text-rose-400 border-rose-500/20', 'Admin HRGA' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'Admin EHS' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'Admin MTC' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'Viewer' => 'bg-violet-500/10 text-violet-400 border-violet-500/20', 'Karyawan' => 'bg-teal-500/10 text-teal-400 border-teal-500/20']; @endphp
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">{{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}</div>
                                <span class="font-medium text-white">{{ $user->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-cyan-400">{{ $user->username }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $user->email }}</td>
                        <td class="px-5 py-3.5">
                            @foreach($user->getRoleNames() as $role)
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $roleColors[$role] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">{{ $role }}</span>
                            @endforeach
                        </td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($user->departemen)->nama ?? '-' }}</td>
                        <td class="px-5 py-3.5"><span class="px-2.5 py-1 text-xs rounded-full {{ $user->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $user->status ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('user-management.user.edit', $user->id) }}" class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('user-management.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan pengguna ini?')">@csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-400 hover:bg-rose-400/10 rounded-lg transition-colors"><i data-lucide="user-x" class="w-4 h-4"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    ${emptyTable(7, 'Belum ada data pengguna.')}
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $users->links() }}</div>
    </div>
`));

write('user-management/user/create.blade.php', layout('Tambah Pengguna', `
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('user-management.user.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
            <h2 class="text-2xl font-bold text-white">Tambah Pengguna Baru</h2>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('user-management.user.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('nama_lengkap') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Username *</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('username') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('email') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Role *</label>
                        <select name="role" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)<option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>@endforeach
                        </select>
                        @error('role') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Departemen</label>
                        <select name="departemen_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemen as $d)<option value="{{ $d->id }}" {{ old('departemen_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>@endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Password *</label>
                        <input type="password" name="password" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('password') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password *</label>
                        <input type="password" name="password_confirmation" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                </div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('user-management.user.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
`));

write('user-management/user/edit.blade.php', layout('Edit Pengguna', `
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('user-management.user.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
            <h2 class="text-2xl font-bold text-white">Edit Pengguna</h2>
        </div>
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
            <form action="{{ route('user-management.user.update', $user->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Role *</label>
                        <select name="role" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            @foreach($roles as $role)<option value="{{ $role->name }}" {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>@endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Departemen</label>
                        <select name="departemen_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemen as $d)<option value="{{ $d->id }}" {{ old('departemen_id', $user->departemen_id) == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>@endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Password Baru <span class="text-gray-500 font-normal">(kosongkan jika tidak ganti)</span></label>
                        <input type="password" name="password" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" {{ old('status', $user->status) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-600 text-indigo-600 focus:ring-indigo-500 bg-gray-900">
                        <span class="text-sm font-medium text-gray-300">Pengguna Aktif</span>
                    </label>
                </div>
                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('user-management.user.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Update</button>
                </div>
            </form>
        </div>
    </div>
`));

// =============================================
// ACTIVITY LOG
// =============================================
write('activity-log/index.blade.php', layout('Activity Log', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Activity Log</h2>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">Waktu</th><th class="px-5 py-4">Pengguna</th><th class="px-5 py-4">Aktivitas</th><th class="px-5 py-4">IP Address</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 text-gray-300">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ optional($log->causer)->nama_lengkap ?? 'Sistem' }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $log->description }}</td>
                        <td class="px-5 py-3.5 font-mono text-gray-400">{{ $log->properties['ip'] ?? '-' }}</td>
                    </tr>
                    ${emptyTable(4, 'Belum ada log aktivitas.')}
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $logs->links() }}</div>
    </div>
`));

// =============================================
// SETTINGS
// =============================================
write('settings/index.blade.php', layout('Pengaturan Sistem', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Pengaturan Sistem</h2>
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="p-6">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 pb-2 border-b border-gray-700/50">Informasi Perusahaan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @php $settings = $settings ?? collect(); @endphp
                        <div><label class="block text-sm font-medium text-gray-300 mb-2">Nama Perusahaan</label>
                            <input type="text" name="perusahaan_nama" value="{{ old('perusahaan_nama', optional($settings->where('key', 'perusahaan_nama')->first())->value) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                        <div><label class="block text-sm font-medium text-gray-300 mb-2">Email Perusahaan</label>
                            <input type="email" name="perusahaan_email" value="{{ old('perusahaan_email', optional($settings->where('key', 'perusahaan_email')->first())->value) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                        <div><label class="block text-sm font-medium text-gray-300 mb-2">Telepon</label>
                            <input type="text" name="perusahaan_telepon" value="{{ old('perusahaan_telepon', optional($settings->where('key', 'perusahaan_telepon')->first())->value) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                        <div><label class="block text-sm font-medium text-gray-300 mb-2">NPWP</label>
                            <input type="text" name="perusahaan_npwp" value="{{ old('perusahaan_npwp', optional($settings->where('key', 'perusahaan_npwp')->first())->value) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                            <textarea name="perusahaan_alamat" rows="3" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('perusahaan_alamat', optional($settings->where('key', 'perusahaan_alamat')->first())->value) }}</textarea></div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
`));

// =============================================
// LAPORAN (Semua Tab)
// =============================================
write('laporan/index.blade.php', layout('Laporan', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Semua Laporan</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('laporan.barang-masuk') }}" class="bg-gray-800/50 hover:bg-gray-700/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex flex-col items-center gap-3 transition-all hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 rounded-xl bg-emerald-500/10 flex items-center justify-center"><i data-lucide="arrow-down-to-line" class="w-7 h-7 text-emerald-400"></i></div>
            <span class="font-semibold text-white">Barang Masuk</span>
        </a>
        <a href="{{ route('laporan.barang-keluar') }}" class="bg-gray-800/50 hover:bg-gray-700/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex flex-col items-center gap-3 transition-all hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 rounded-xl bg-rose-500/10 flex items-center justify-center"><i data-lucide="arrow-up-from-line" class="w-7 h-7 text-rose-400"></i></div>
            <span class="font-semibold text-white">Barang Keluar</span>
        </a>
        <a href="{{ route('laporan.stok') }}" class="bg-gray-800/50 hover:bg-gray-700/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex flex-col items-center gap-3 transition-all hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center"><i data-lucide="layers" class="w-7 h-7 text-blue-400"></i></div>
            <span class="font-semibold text-white">Stok Barang</span>
        </a>
        <a href="{{ route('laporan.nota') }}" class="bg-gray-800/50 hover:bg-gray-700/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex flex-col items-center gap-3 transition-all hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 rounded-xl bg-amber-500/10 flex items-center justify-center"><i data-lucide="receipt" class="w-7 h-7 text-amber-400"></i></div>
            <span class="font-semibold text-white">Nota Transaksi</span>
        </a>
    </div>
`));

// NOTIFIKASI
write('notifikasi/index.blade.php', layout('Notifikasi', `
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Notifikasi</h2>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <a href="{{ route('notifikasi.mark-all-read') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors">Tandai semua dibaca</a>
        @endif
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden divide-y divide-gray-700/50">
        @forelse(auth()->user()->notifications as $notif)
        <a href="{{ route('notifikasi.show', $notif->id) }}" class="flex items-start gap-4 p-5 hover:bg-gray-700/20 transition-colors {{ $notif->read_at ? 'opacity-70' : '' }}">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center flex-shrink-0">
                <i data-lucide="{{ $notif->data['icon'] ?? 'bell' }}" class="w-5 h-5 text-indigo-400"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-white">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                <p class="text-sm text-gray-400 mt-0.5">{{ $notif->data['message'] ?? '' }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
            </div>
            @if(!$notif->read_at)
            <div class="w-2.5 h-2.5 rounded-full bg-cyan-400 mt-1.5 flex-shrink-0"></div>
            @endif
        </a>
        @empty
        <div class="px-5 py-16 text-center text-gray-500">
            <i data-lucide="bell-off" class="w-12 h-12 mx-auto mb-3 text-gray-600"></i>
            <p>Tidak ada notifikasi.</p>
        </div>
        @endforelse
    </div>
`));

console.log('All views generated successfully!');
