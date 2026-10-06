{{-- Sidebar nav item component --}}
@props([
    'href' => '#',
    'icon' => 'circle',
    'active' => false,
    'sub' => false,
    'badge' => null,
])

<a href="{{ $href }}"
   class="flex items-center rounded-lg text-sm font-medium transition-all duration-200 group relative"
   :class="[
       sidebarOpen ? 'gap-3 px-3 py-2' : 'justify-center py-3',
       sidebarOpen && '{{ $sub ? 'pl-9' : '' }}',
       '{{ $active ? 'text-white bg-white/10 border border-white/10 shadow-sm' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}'
   ]"
   title="{{ trim(strip_tags($slot)) }}">
    
    @if($active)
    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-gradient-to-b from-cyan-400 to-indigo-500 rounded-r-full"
          style="box-shadow: 0 0 8px rgba(34,211,238,0.5)"></span>
    @endif
    
    <div class="relative flex items-center justify-center">
        <i data-lucide="{{ $icon }}" class="flex-shrink-0 transition-all duration-200 {{ $active ? 'text-cyan-400' : 'group-hover:text-gray-300' }}"
           :class="sidebarOpen ? 'w-4 h-4' : 'w-5 h-5'"></i>
        @if($badge)
            <span x-show="!sidebarOpen" class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-red-500"></span>
        @endif
    </div>

    <span class="flex-1 flex items-center truncate" x-show="sidebarOpen" x-transition.opacity.duration.300ms>
        <span class="truncate">{{ $slot }}</span>
        @if($badge)
            <span class="ml-auto flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white px-1">{{ $badge }}</span>
        @endif
    </span>
</a>
