<x-app-layout>
    <x-slot name="title">Barang Keluar (Request Saya)</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-sm text-gray-400 mt-1">Daftar permintaan barang yang pernah Anda ajukan.</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-request')"
                class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4"></i> Ajukan Permintaan
        </button>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr>
                        <th class="px-5 py-4">No. Transaksi</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Departemen</th>
                        <th class="px-5 py-4">Gedung</th>
                        <th class="px-5 py-4">Keperluan</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($records as $item)
                    @php
                        $statusClass = [
                            'Menunggu Approval' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                            'Diproses'          => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                            'Selesai'           => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                            'Ditolak'           => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                        ][$item->status] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20';
                    @endphp
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-cyan-400 font-medium">{{ $item->no_transaksi }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->departemen)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ optional($item->gedung)->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-300 max-w-xs truncate">{{ $item->keperluan }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $statusClass }}">{{ $item->status }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('transaksi.request.show', $item->id) }}" class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-colors inline-flex" title="Detail">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada request barang. Klik tombol "Ajukan Permintaan" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $records->links() }}</div>
    </div>

    {{-- Modal Ajukan Permintaan --}}
    <x-modal name="create-request" title="Ajukan Permintaan Barang" max-width="4xl">
        <form action="{{ route('transaksi.request.store') }}" method="POST" x-data="requestForm()" x-init="init()">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-white border-b border-gray-700/50 pb-2">Informasi Permintaan</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Departemen <span class="text-rose-400">*</span></label>
                        <select name="departemen_id" x-model="departemenId" @change="loadBarang()" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemen as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Gedung <span class="text-rose-400">*</span></label>
                        <select name="gedung_id" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Gedung --</option>
                            @foreach($gedung as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Keperluan <span class="text-rose-400">*</span></label>
                        <input type="text" name="keperluan" value="{{ old('keperluan') }}" placeholder="Contoh: Operasional Bulanan Q4" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                {{-- Add Barang Panel --}}
                <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-white border-b border-gray-700/50 pb-2">Tambah Item Barang</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Barang</label>
                        <select x-model="selectedBarangId" :disabled="!departemenId" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none disabled:opacity-50">
                            <option value="">-- Pilih Barang --</option>
                            <template x-for="b in barangList" :key="b.id">
                                <option :value="b.id" x-text="b.kode_barang + ' - ' + b.nama"></option>
                            </template>
                        </select>
                        <p x-show="!departemenId" class="text-xs text-amber-400 mt-1">Pilih departemen terlebih dahulu.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah</label>
                        <input type="number" x-model="selectedQty" min="1" class="w-full bg-gray-900/50 border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <button type="button" @click="addItem()" class="w-full px-4 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white rounded-lg font-medium transition-all">
                        <i data-lucide="plus-circle" class="w-4 h-4 inline-block mr-1"></i> Tambah ke Daftar
                    </button>
                </div>
            </div>

            {{-- Items List --}}
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden mb-6">
                <div class="p-5 border-b border-gray-700/50 flex justify-between items-center">
                    <h3 class="font-semibold text-white">Daftar Item yang Diminta (<span x-text="items.length"></span>)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                            <tr><th class="px-5 py-3">No</th><th class="px-5 py-3">Kode</th><th class="px-5 py-3">Nama Barang</th><th class="px-5 py-3">Qty</th><th class="px-5 py-3">Satuan</th><th class="px-5 py-3 text-right">Hapus</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="hover:bg-gray-700/20">
                                    <td class="px-5 py-3 text-gray-400" x-text="index+1"></td>
                                    <td class="px-5 py-3 font-mono text-cyan-400" x-text="item.kode_barang"></td>
                                    <td class="px-5 py-3 text-white" x-text="item.nama"></td>
                                    <td class="px-5 py-3">
                                        <input type="number" :name="'items['+index+'][qty]'" x-model="item.qty" min="1" class="w-20 bg-gray-900 border border-gray-700 text-white rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                        <input type="hidden" :name="'items['+index+'][barang_id]'" :value="item.id">
                                    </td>
                                    <td class="px-5 py-3 text-gray-300" x-text="item.satuan"></td>
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
                <button type="button" @click="$dispatch('close-modal', 'create-request')" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">Batal</button>
                <button type="submit" :disabled="items.length === 0" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                    <i data-lucide="send" class="w-4 h-4 inline-block mr-1"></i> Kirim Permintaan
                </button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
    function requestForm() {
        return {
            departemenId: '',
            selectedBarangId: '',
            selectedQty: 1,
            barangList: [],
            items: [],
            async init() {},
            async loadBarang() {
                if (!this.departemenId) { this.barangList = []; return; }
                // Use the correct API or fallback to fetching all active barang if the API isn't ready
                try {
                    const res = await fetch(`/api/barang-by-departemen/${this.departemenId}`);
                    if(res.ok) {
                        this.barangList = await res.json();
                    } else {
                        // Fallback implementation if API route is not implemented
                        const fallback = await fetch('/api/barang');
                        if (fallback.ok) {
                            const all = await fallback.json();
                            this.barangList = all; // Fallback to all items
                        }
                    }
                } catch(e) {
                    console.error(e);
                }
            },
            addItem() {
                if (!this.selectedBarangId || this.selectedQty < 1) return;
                const barang = this.barangList.find(b => b.id == this.selectedBarangId);
                if (!barang) return;
                const existing = this.items.find(i => i.id == this.selectedBarangId);
                if (existing) { existing.qty += parseInt(this.selectedQty); }
                else { this.items.push({ ...barang, qty: parseInt(this.selectedQty) }); }
                this.selectedBarangId = '';
                this.selectedQty = 1;
            },
            removeItem(index) { this.items.splice(index, 1); }
        }
    }
    </script>
    @endpush
</x-app-layout>
