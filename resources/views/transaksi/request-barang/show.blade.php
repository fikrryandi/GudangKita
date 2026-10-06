<x-app-layout>
    <x-slot name="title">Detail Barang Keluar (Request)</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('transaksi.request.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden p-6">
            <p class="text-gray-400">Detail dari data barang keluar (request) ini akan ditampilkan di sini.</p>
        </div>
    </div>
</x-app-layout>