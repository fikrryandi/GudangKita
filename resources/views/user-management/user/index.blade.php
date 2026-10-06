<x-app-layout>
    <x-slot name="title">Manajemen Pengguna</x-slot>

    <div class="flex justify-between items-center mb-6">
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
                                        @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada data pengguna.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $users->links() }}</div>
    </div>


</x-app-layout>