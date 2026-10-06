<x-app-layout>
    <x-slot name="title">Barang Masuk</x-slot>

    <div class="flex justify-between items-center mb-6">
        <button @click="$dispatch('open-modal', 'create-barang-masuk')"
                class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4"></i> Catat Barang Masuk
        </button>
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
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada data barang masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $records->links() }}</div>
    </div>

    {{-- Modal Tambah Barang Masuk --}}
    <x-modal name="create-barang-masuk" title="Catat Barang Masuk Baru" max-width="4xl">
        <form action="{{ route('transaksi.barang-masuk.store') }}" method="POST" x-data="barangMasukForm()">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-white border-b border-gray-700/50 pb-2">Informasi Transaksi</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Masuk <span class="text-rose-400">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('tanggal') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Departemen Tujuan <span class="text-rose-400">*</span></label>
                        <select name="departemen_id" x-model="departemenId" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemen as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                        @error('departemen_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Gedung / Lokasi <span class="text-rose-400">*</span></label>
                        <select name="gedung_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Gedung --</option>
                            @foreach($gedung as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                        @error('gedung_id') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Supplier</label>
                        <select name="supplier_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Tanpa Supplier (Masuk Internal) --</option>
                            {{-- Need to fetch suppliers manually since it's not passed to index, wait, wait, I can just fetch via API or adjust controller. Let's fix controller later to pass supplier variable. --}}
                            @foreach(\App\Models\Supplier::where('status', true)->get() as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">No. Surat Jalan</label>
                        <input type="text" name="no_surat_jalan" value="{{ old('no_surat_jalan') }}" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Kondisi Barang <span class="text-rose-400">*</span></label>
                        <select name="kondisi" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                    </div>
                </div>

                {{-- Add Barang Panel --}}
                <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-white border-b border-gray-700/50 pb-2">Tambah Item Barang</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Barang</label>
                        <select x-model="selectedBarangId" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Barang --</option>
                            <template x-for="b in barangList" :key="b.id">
                                <option :value="b.id" x-text="b.kode_barang + ' - ' + b.nama"></option>
                            </template>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah (Qty)</label>
                            <input type="number" x-model="selectedQty" min="1" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Harga Satuan (Rp)</label>
                            <input type="number" x-model="selectedHarga" min="0" step="100" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <p class="text-xs text-gray-500 mt-1" x-show="selectedHarga > 0">
                                <span class="text-cyan-400">Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedHarga)"></span></span>
                                &nbsp;(dari master data, bisa diubah)
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="addItem()" class="w-full px-4 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white rounded-lg font-medium transition-all mt-2">
                        <i data-lucide="plus-circle" class="w-4 h-4 inline-block mr-1"></i> Tambah ke Daftar
                    </button>
                </div>
            </div>

            {{-- Items List --}}
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden mb-6">
                <div class="p-5 border-b border-gray-700/50 flex justify-between items-center">
                    <h3 class="font-semibold text-white">Daftar Barang Masuk (<span x-text="items.length"></span>)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                            <tr><th class="px-5 py-3">No</th><th class="px-5 py-3">Barang</th><th class="px-5 py-3">Qty</th><th class="px-5 py-3">Harga Satuan</th><th class="px-5 py-3">Total</th><th class="px-5 py-3 text-right">Hapus</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="hover:bg-gray-700/20">
                                    <td class="px-5 py-3 text-gray-400" x-text="index+1"></td>
                                    <td class="px-5 py-3">
                                        <p class="text-white font-medium" x-text="item.nama"></p>
                                        <p class="text-xs text-cyan-400 font-mono" x-text="item.kode_barang"></p>
                                        <p class="text-xs text-gray-500" x-text="item.satuan"></p>
                                        <input type="hidden" :name="'items['+index+'][barang_id]'" :value="item.id">
                                    </td>
                                    <td class="px-5 py-3">
                                        <input type="number" :name="'items['+index+'][qty]'" x-model="item.qty" min="1" class="w-20 bg-gray-900 border border-gray-700 text-white rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                    </td>
                                    <td class="px-5 py-3">
                                        <input type="number" :name="'items['+index+'][harga_satuan]'" x-model="item.harga_satuan" min="0" class="w-36 bg-gray-900 border border-gray-700 text-white rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                    </td>
                                    <td class="px-5 py-3 font-medium text-emerald-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.qty * item.harga_satuan)"></td>
                                    <td class="px-5 py-3 text-right"><button type="button" @click="removeItem(index)" class="p-1.5 text-rose-400 hover:bg-rose-400/10 rounded-lg transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button></td>
                                </tr>
                            </template>
                            <tr x-show="items.length === 0">
                                <td colspan="6" class="px-5 py-8 text-center text-gray-500">Belum ada item ditambahkan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-700/50">
                <button type="button" @click="$dispatch('close-modal', 'create-barang-masuk')" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</button>
                <button type="submit" :disabled="items.length === 0" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
    function barangMasukForm() {
        return {
            departemenId: '',
            selectedBarangId: '',
            selectedQty: 1,
            selectedHarga: 0,
            barangList: [], 
            items: [],
            async init() {
                const res = await fetch('/api/barang');
                this.barangList = await res.json();
                
                this.$watch('selectedBarangId', (value) => {
                    if (value) {
                        const barang = this.barangList.find(b => b.id == value);
                        if (barang) {
                            this.selectedHarga = barang.harga_satuan;
                        }
                    } else {
                        this.selectedHarga = 0;
                    }
                });
            },
            addItem() {
                if (!this.selectedBarangId || this.selectedQty < 1) return;
                const barang = this.barangList.find(b => b.id == this.selectedBarangId);
                if (!barang) return;
                const existing = this.items.find(i => i.id == this.selectedBarangId);
                if (existing) {
                    existing.qty += parseInt(this.selectedQty);
                    existing.harga_satuan = parseInt(this.selectedHarga);
                } else {
                    this.items.push({
                        id: this.selectedBarangId,
                        kode_barang: barang.kode_barang,
                        nama: barang.nama,
                        satuan: barang.satuan,
                        qty: parseInt(this.selectedQty),
                        harga_satuan: parseInt(this.selectedHarga)
                    });
                }
                this.selectedBarangId = '';
                this.selectedQty = 1;
                this.selectedHarga = 0;
            },
            removeItem(index) { this.items.splice(index, 1); }
        }
    }
    </script>
    @endpush
</x-app-layout>