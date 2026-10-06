<x-app-layout>
    <x-slot name="title">Barang Keluar (Request)</x-slot>

    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('transaksi.request.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4 inline-block mr-1"></i> Tambah Baru
        </a>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden p-6">
        <p class="text-gray-400">Halaman daftar barang keluar (request) akan ditampilkan di sini.</p>
    </div>
</x-app-layout>