<x-app-layout>
    <x-slot name="title">Edit Pengguna</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('user-management.user.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
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


</x-app-layout>