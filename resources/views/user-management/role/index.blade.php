<x-app-layout>
    <x-slot name="title">Manajemen Role</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Role List --}}
        <div class="lg:col-span-2">
            <div class="bg-[#111827]/80 backdrop-blur-xl border border-blue-500/20 rounded-2xl shadow-[0_0_20px_rgba(37,99,235,0.05)] overflow-hidden">
                <div class="p-5 border-b border-white/5 flex items-center justify-between bg-white/5">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i data-lucide="shield" class="w-5 h-5 text-blue-400"></i> Daftar Role
                    </h3>
                    <span class="text-xs text-blue-300 bg-blue-500/10 border border-blue-500/20 rounded-lg px-2 py-1">{{ $roles->count() }} Role</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-blue-200 uppercase bg-[#0a1128]/50 border-b border-white/5">
                            <tr>
                                <th class="px-5 py-3">No</th>
                                <th class="px-5 py-3">Nama Role</th>
                                <th class="px-5 py-3 text-center">Pengguna</th>
                                <th class="px-5 py-3 text-center">Izin</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($roles as $i => $role)
                            @php
                                $colors = [
                                    'Super Admin' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                                    'Admin HRGA'  => 'bg-blue-500/15 text-blue-400 border-blue-500/30',
                                    'Admin EHS'   => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                    'Admin MTC'   => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                                    'Karyawan'    => 'bg-teal-500/15 text-teal-400 border-teal-500/30',
                                    'Viewer'      => 'bg-violet-500/15 text-violet-400 border-violet-500/30',
                                ];
                                $cls = $colors[$role->name] ?? 'bg-gray-500/15 text-gray-400 border-gray-500/30';
                            @endphp
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-5 py-4 text-gray-400">{{ $i+1 }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-3 py-1 rounded-lg text-xs font-semibold border {{ $cls }}">{{ $role->name }}</span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="text-white font-bold">{{ $role->users_count }}</span>
                                    <span class="text-gray-500 text-xs ml-1">pengguna</span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="text-white font-bold">{{ $role->permissions_count }}</span>
                                    <span class="text-gray-500 text-xs ml-1">izin</span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Edit --}}
                                        <button onclick="openEditModal({{ $role->id }}, '{{ $role->name }}')"
                                                class="p-2 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/20 transition-colors">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        {{-- Delete --}}
                                        @if($role->users_count == 0)
                                        <form method="POST" action="{{ route('user-management.role.destroy', $role) }}"
                                              onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                        @else
                                        <span class="p-2 rounded-lg bg-gray-700/30 text-gray-600 cursor-not-allowed" title="Role masih dipakai">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">Belum ada role.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT: Tambah Role --}}
        <div>
            <div class="bg-[#111827]/80 backdrop-blur-xl border border-blue-500/20 rounded-2xl shadow-[0_0_20px_rgba(37,99,235,0.05)]">
                <div class="p-5 border-b border-white/5 bg-white/5">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-blue-400"></i> Tambah Role Baru
                    </h3>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('user-management.role.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs text-blue-200 font-semibold mb-2 uppercase tracking-wide">Nama Role</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="cth: Admin Logistik"
                                   class="w-full bg-[#0a1128] border border-blue-500/30 text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none placeholder-gray-600">
                            @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs text-blue-200 font-semibold mb-3 uppercase tracking-wide">Pilih Izin (Permissions)</label>
                            <div class="bg-[#0a1128] p-4 rounded-xl border border-blue-500/20 max-h-64 overflow-y-auto custom-scrollbar">
                                @forelse($permissions as $group => $perms)
                                <div class="mb-4 last:mb-0">
                                    <h4 class="text-sm font-bold text-white capitalize mb-2 border-b border-white/5 pb-1">{{ $group }}</h4>
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach($perms as $p)
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="permissions[]" value="{{ $p->name }}" 
                                                   class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-600 focus:ring-2 cursor-pointer">
                                            <span class="text-sm text-gray-300 group-hover:text-white transition-colors">{{ $p->name }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @empty
                                <p class="text-xs text-gray-500 italic">Belum ada permission tersedia.</p>
                                @endforelse
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 rounded-xl font-semibold text-sm text-white transition-all"
                                style="background:linear-gradient(135deg,#2563eb,#dc2626); box-shadow:0 0 15px rgba(37,99,235,0.3);">
                            Simpan Role
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="mt-4 bg-[#111827]/80 backdrop-blur-xl border border-amber-500/20 rounded-2xl p-4 shadow-[0_0_15px_rgba(245,158,11,0.05)]">
                <div class="flex gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
                    <div class="text-xs text-gray-400 leading-relaxed">
                        Role yang sedang digunakan oleh pengguna <span class="text-amber-400 font-semibold">tidak bisa dihapus</span>. Pindahkan pengguna ke role lain terlebih dahulu.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-[#111827] border border-blue-500/30 rounded-2xl shadow-2xl w-full max-w-md p-6" style="box-shadow:0 0 40px rgba(37,99,235,0.25);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="pencil" class="w-5 h-5 text-blue-400"></i> Edit Role
                </h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="mb-5">
                    <label class="block text-xs text-blue-200 font-semibold mb-2 uppercase tracking-wide">Nama Role</label>
                    <input type="text" name="name" id="editRoleName" required
                           class="w-full bg-[#0a1128] border border-blue-500/30 text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="mb-6">
                    <label class="block text-xs text-blue-200 font-semibold mb-3 uppercase tracking-wide">Pilih Izin (Permissions)</label>
                    <div class="bg-[#0a1128] p-4 rounded-xl border border-blue-500/20 max-h-64 overflow-y-auto custom-scrollbar">
                        @forelse($permissions as $group => $perms)
                        <div class="mb-4 last:mb-0">
                            <h4 class="text-sm font-bold text-white capitalize mb-2 border-b border-white/5 pb-1">{{ $group }}</h4>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach($perms as $p)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="permissions[]" value="{{ $p->name }}" id="edit_perm_{{ $p->id }}"
                                           class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-600 focus:ring-2 cursor-pointer edit-permission-checkbox">
                                    <span class="text-sm text-gray-300 group-hover:text-white transition-colors">{{ $p->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-gray-500 italic">Belum ada permission tersedia.</p>
                        @endforelse
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeEditModal()"
                            class="flex-1 py-2.5 rounded-xl font-semibold text-sm text-gray-300 bg-white/10 hover:bg-white/15 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-xl font-semibold text-sm text-white transition-all"
                            style="background:linear-gradient(135deg,#2563eb,#dc2626); box-shadow:0 0 15px rgba(37,99,235,0.3);">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModal(id, name) {
            document.getElementById('editForm').action = `/user-management/role/${id}`;
            document.getElementById('editRoleName').value = name;
            
            // Reset checkboxes
            document.querySelectorAll('.edit-permission-checkbox').forEach(cb => cb.checked = false);
            
            // Fetch role permissions via API or inline logic
            // Because inline is hard without passing the data, we can pass it via data attribute or just do a quick fetch
            fetch(`/user-management/role/${id}/permissions`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(permName => {
                        const cb = document.querySelector(`.edit-permission-checkbox[value="${permName}"]`);
                        if(cb) cb.checked = true;
                    });
                });

            document.getElementById('editModal').classList.remove('hidden');
            lucide.createIcons();
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>
