<x-app-layout>
    <x-slot name="title">Notifikasi</x-slot>

    <div class="flex justify-between items-center mb-6">
        @if(auth()->user()->unreadNotifications->count() > 0)
        <a href="{{ route('notifikasi.mark-all-read') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors">Tandai semua dibaca</a>
        @endif
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden divide-y divide-gray-700/50">
        @forelse(auth()->user()->notifications as $notif)
        <a href="{{ route('notifikasi.show', $notif->id) }}" class="flex items-start gap-4 p-5 hover:bg-gray-700/20 transition-colors {{ $notif->read_at ? 'opacity-70' : '' }}">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center flex-shrink-0">
                <i data-lucide="{{ $notif->data['icon'] ?? 'bell' }}" class="w-5 h-5 text-indigo-400"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-white">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                <p class="text-sm text-gray-400 mt-0.5">{{ $notif->data['message'] ?? '' }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
            </div>
            @if(!$notif->read_at)
            <div class="w-2.5 h-2.5 rounded-full bg-cyan-400 mt-1.5 flex-shrink-0"></div>
            @endif
        </a>
        @empty
        <div class="px-5 py-16 text-center text-gray-500">
            <i data-lucide="bell-off" class="w-12 h-12 mx-auto mb-3 text-gray-600"></i>
            <p>Tidak ada notifikasi.</p>
        </div>
        @endforelse
    </div>


</x-app-layout>