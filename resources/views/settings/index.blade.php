<x-app-layout>
    <x-slot name="title">Pengaturan Sistem</x-slot>

    <div class="max-w-3xl mx-auto">

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl">
            <div class="p-6 border-b border-gray-700/50">
                <h3 class="text-lg font-semibold text-white">Konfigurasi Sistem GudangKita</h3>
            </div>
            <form action="{{ route('settings.update') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Nama & Identitas</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nama Perusahaan <span class="text-rose-400">*</span></label>
                            <input type="text" name="nama_perusahaan"
                                value="{{ old('nama_perusahaan', $settings['nama_perusahaan'] ?? '') }}"
                                class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            @error('nama_perusahaan') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Format Nomor Transaksi</label>
                            <input type="text" name="format_nomor_transaksi"
                                value="{{ old('format_nomor_transaksi', $settings['format_nomor_transaksi'] ?? 'PREFIX-YYYYMMDD-####') }}"
                                placeholder="BM-YYYYMMDD-####"
                                class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <p class="text-xs text-gray-500 mt-1">Prefix diatur otomatis: BM (Barang Masuk), BK (Barang Keluar), TRF (Transfer), ADJ (Adjustment)</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-700/50 pt-6">
                    <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Konfigurasi Transaksi</h4>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="transfer_antar_departemen" value="0">
                            <input type="checkbox" name="transfer_antar_departemen" value="1"
                                {{ old('transfer_antar_departemen', $settings['transfer_antar_departemen'] ?? '0') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-600 text-indigo-600 focus:ring-indigo-500 bg-gray-900">
                            <div>
                                <span class="text-sm font-medium text-gray-300">Izinkan Transfer Antar Departemen</span>
                                <p class="text-xs text-gray-500">Mengizinkan pemindahan barang dari satu departemen ke departemen lain.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-700/50">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>