<x-app-layout>
    <x-slot name="title">Profil Saya</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center gap-4 mb-6">
        </div>

        {{-- Info Profil --}}
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-700/50">
                <h3 class="text-lg font-semibold text-white">Informasi Akun</h3>
                <p class="text-sm text-gray-400 mt-1">Perbarui informasi profil dan alamat email Anda.</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap ?? $user->name) }}"
                                class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            @error('nama_lengkap') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}"
                                class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                            Simpan Perubahan
                        </button>
                    </div>

                    @if(session('status') === 'profile-updated')
                    <div class="text-sm text-emerald-400 mt-2">Profil berhasil diperbarui.</div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Ubah Password --}}
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-700/50">
                <h3 class="text-lg font-semibold text-white">Ubah Password</h3>
                <p class="text-sm text-gray-400 mt-1">Pastikan akun Anda menggunakan password yang kuat dan aman.</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password"
                            class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        @error('current_password', 'updatePassword') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Password Baru</label>
                            <input type="password" name="password"
                                class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            @error('password', 'updatePassword') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                            Ubah Password
                        </button>
                    </div>

                    @if(session('status') === 'password-updated')
                    <div class="text-sm text-emerald-400 mt-2">Password berhasil diperbarui.</div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
