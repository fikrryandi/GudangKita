<x-app-layout>
    <x-slot name="title">Laporan</x-slot>

    <div class="flex justify-between items-center mb-6">
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('laporan.barang-masuk') }}" class="bg-gray-800/50 hover:bg-gray-700/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex flex-col items-center gap-3 transition-all hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 rounded-xl bg-emerald-500/10 flex items-center justify-center"><i data-lucide="arrow-down-to-line" class="w-7 h-7 text-emerald-400"></i></div>
            <span class="font-semibold text-white">Barang Masuk</span>
        </a>
        <a href="{{ route('laporan.barang-keluar') }}" class="bg-gray-800/50 hover:bg-gray-700/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex flex-col items-center gap-3 transition-all hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 rounded-xl bg-rose-500/10 flex items-center justify-center"><i data-lucide="arrow-up-from-line" class="w-7 h-7 text-rose-400"></i></div>
            <span class="font-semibold text-white">Barang Keluar</span>
        </a>
        <a href="{{ route('laporan.stok') }}" class="bg-gray-800/50 hover:bg-gray-700/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex flex-col items-center gap-3 transition-all hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center"><i data-lucide="layers" class="w-7 h-7 text-blue-400"></i></div>
            <span class="font-semibold text-white">Stok Barang</span>
        </a>
        <a href="{{ route('laporan.nota') }}" class="bg-gray-800/50 hover:bg-gray-700/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-6 flex flex-col items-center gap-3 transition-all hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 rounded-xl bg-amber-500/10 flex items-center justify-center"><i data-lucide="receipt" class="w-7 h-7 text-amber-400"></i></div>
            <span class="font-semibold text-white">Nota Transaksi</span>
        </a>
    </div>


</x-app-layout>