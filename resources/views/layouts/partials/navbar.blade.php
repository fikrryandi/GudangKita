{{-- Navbar / Topbar --}}
<header class="sticky top-0 z-20 flex items-center justify-between px-4 md:px-6 h-16 border-b border-white/5"
        style="background: rgba(11,15,26,0.85); backdrop-filter: blur(16px);">

    <!-- Left: Toggle + Page Title -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        <div class="hidden sm:block">
            <h2 class="text-lg font-bold text-white tracking-wide">{{ $title ?? 'Dashboard' }}</h2>
        </div>
    </div>

    <!-- Right: Date+Time + Bell + User -->
    <div class="flex items-center gap-3">

        <!-- Date & Live Clock -->
        <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl border border-white/10 bg-white/5">
            <i data-lucide="calendar-clock" class="w-4 h-4 text-blue-400 flex-shrink-0"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-xs font-semibold text-gray-100">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                <span class="text-xs text-blue-300 font-mono tabular-nums" id="live-clock">{{ now()->format('H:i:s') }}</span>
            </div>
        </div>

        <!-- Notifications Bell -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false"
                    class="relative p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                <i data-lucide="bell" class="w-5 h-5"></i>
                @php $unreadCount = auth()->user()->unreadNotifications->count() @endphp
                @if($unreadCount > 0)
                <span class="absolute -top-0.5 -right-0.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white px-0.5 animate-pulse">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
                @endif
            </button>

            <!-- Dropdown -->
            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 mt-2 w-80 rounded-xl border border-white/10 shadow-2xl z-50 overflow-hidden"
                 style="background: rgba(17,24,39,0.97); backdrop-filter: blur(20px);">
                <div class="flex items-center justify-between px-4 py-3 border-b border-white/5">
                    <span class="text-sm font-semibold text-white">Notifikasi</span>
                    @if($unreadCount > 0)
                    <a href="{{ route('notifikasi.mark-all-read') }}" class="text-xs text-blue-400 hover:text-blue-300">Tandai semua dibaca</a>
                    @endif
                </div>
                <div class="max-h-72 overflow-y-auto divide-y divide-white/5">
                    @forelse(auth()->user()->notifications->take(5) as $notif)
                    <a href="{{ route('notifikasi.show', $notif->id) }}"
                       class="flex gap-3 px-4 py-3 hover:bg-white/5 transition-colors {{ $notif->read_at ? 'opacity-60' : '' }}">
                        <div class="mt-0.5 w-8 h-8 rounded-lg bg-{{ $notif->data['color'] ?? 'blue' }}-500/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="{{ $notif->data['icon'] ?? 'bell' }}" class="w-4 h-4 text-{{ $notif->data['color'] ?? 'blue' }}-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-200 leading-tight">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $notif->data['message'] ?? '' }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notif->read_at)
                        <div class="w-2 h-2 rounded-full bg-blue-400 mt-2 flex-shrink-0"></div>
                        @endif
                    </a>
                    @empty
                    <div class="px-4 py-8 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>
                    @endforelse
                </div>
                <div class="px-4 py-3 border-t border-white/5">
                    <a href="{{ route('notifikasi.index') }}" class="text-xs text-center block text-blue-400 hover:text-blue-300">
                        Lihat semua notifikasi
                    </a>
                </div>
            </div>
        </div>

        <!-- User Avatar Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false"
                    class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-red-600 flex items-center justify-center shadow-[0_0_10px_rgba(37,99,235,0.5)]">
                    <span class="text-xs font-bold text-white">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}</span>
                </div>
                <span class="hidden md:block text-sm font-medium text-gray-300">{{ auth()->user()->nama_lengkap }}</span>
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 mt-2 w-52 rounded-xl border border-white/10 shadow-2xl z-50 overflow-hidden"
                 style="background: rgba(17,24,39,0.97); backdrop-filter: blur(20px);">
                <div class="px-4 py-3 border-b border-white/5">
                    <p class="text-sm font-semibold text-white">{{ auth()->user()->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->getRoleNames()->first() }}</p>
                </div>
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 transition-colors">
                        <i data-lucide="user" class="w-4 h-4"></i> Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Live clock — updates every second
    (function() {
        function updateClock() {
            const el = document.getElementById('live-clock');
            if (!el) return;
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            el.textContent = `${h}:${m}:${s}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    })();
</script>
