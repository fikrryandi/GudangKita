<x-app-layout>
    <x-slot name="title">Detail Notifikasi</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('notifikasi.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden p-6">
            <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-700/50">
                <div class="p-3 bg-indigo-500/10 rounded-xl border border-indigo-500/20 text-indigo-400">
                    <i data-lucide="bell" class="w-8 h-8"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-white">{{ $notifikasi->judul }}</h3>
                    <p class="text-sm text-gray-400 mt-1">Diterima: {{ \Carbon\Carbon::parse($notifikasi->created_at)->translatedFormat('l, d F Y H:i') }}</p>
                </div>
            </div>
            
            <div class="prose prose-invert prose-p:text-gray-300 prose-p:leading-relaxed max-w-none">
                <p>{{ $notifikasi->pesan }}</p>
            </div>
            
            @if($notifikasi->link)
            <div class="mt-8 pt-6 border-t border-gray-700/50">
                <a href="{{ $notifikasi->link }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                    Lihat Transaksi Terkait <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>