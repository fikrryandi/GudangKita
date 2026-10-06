<x-app-layout>
    <x-slot name="title">Transfer Barang Baru</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('transaksi.transfer.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl" x-data="{ departemenAsal: '' }">
            <form action="{{ route('transaksi.transfer.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Barang yang Ditransfer <span class="text-rose-400">*</span></label>
                        <select name="barang_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Barang --</option>
                            {{-- Barang will be filtered after JS or show all --}}
                        </select>
                        @error('barang_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-400 mb-3 uppercase tracking-wider">Lokasi Asal</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Departemen Asal *</label>
                                <select name="departemen_asal_id" x-model="departemenAsal" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="">-- Pilih --</option>
                                    @foreach($departemen as $d)<option value="{{ $d->id }}">{{ $d->nama }}</option>@endforeach
                                </select>
                                @error('departemen_asal_id') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Gedung Asal *</label>
                                <select name="gedung_asal_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="">-- Pilih --</option>
                                    @foreach($gedung as $g)<option value="{{ $g->id }}">{{ $g->nama }}</option>@endforeach
                                </select>
                                @error('gedung_asal_id') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-400 mb-3 uppercase tracking-wider">Lokasi Tujuan</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Departemen Tujuan *</label>
                                <select name="departemen_tujuan_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="">-- Pilih --</option>
                                    @foreach($departemen as $d)<option value="{{ $d->id }}">{{ $d->nama }}</option>@endforeach
                                </select>
                                @error('departemen_tujuan_id') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Gedung Tujuan *</label>
                                <select name="gedung_tujuan_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="">-- Pilih --</option>
                                    @foreach($gedung as $g)<option value="{{ $g->id }}">{{ $g->nama }}</option>@endforeach
                                </select>
                                @error('gedung_tujuan_id') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah Transfer *</label>
                        <input type="number" name="qty" value="{{ old('qty', 1) }}" min="1" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('qty') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Alasan Transfer *</label>
                    <textarea name="alasan" rows="3" placeholder="Jelaskan alasan transfer barang ini..." class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('alasan') }}</textarea>
                    @error('alasan') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 flex gap-3 justify-end border-t border-gray-700/50">
                    <a href="{{ route('transaksi.transfer.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">Proses Transfer</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>