{{-- Sidebar collapsible group component --}}
@props([
    'label' => '',
    'icon' => 'folder',
    'active' => false,
])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }">
    <button @click="if(!sidebarOpen) sidebarOpen = true; else open = !open"
            class="w-full flex items-center rounded-lg text-sm font-medium text-gray-400 hover:text-gray-200 hover:bg-white/5 transition-all duration-200 relative group"
            :class="sidebarOpen ? 'gap-3 px-3 py-2' : 'justify-center py-3'"
            title="{{ $label }}">
        <i data-lucide="{{ $icon }}" class="flex-shrink-0 transition-all duration-200" :class="sidebarOpen ? 'w-4 h-4' : 'w-5 h-5'"></i>
        <span class="flex-1 text-left whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.300ms>{{ $label }}</span>
        <i data-lucide="chevron-right" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-90' : ''" x-show="sidebarOpen"></i>
    </button>
    
    <div x-show="open && sidebarOpen" x-collapse x-cloak class="mt-0.5 space-y-0.5">
        {{ $slot }}
    </div>
</div>
