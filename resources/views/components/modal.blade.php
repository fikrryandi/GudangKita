@props([
    'name' => 'modal',
    'title' => '',
    'maxWidth' => '2xl',
])

@php
$widths = [
    'sm'  => 'max-w-sm',
    'md'  => 'max-w-md',
    'lg'  => 'max-w-lg',
    'xl'  => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
];
$widthClass = $widths[$maxWidth] ?? 'max-w-2xl';
@endphp

<div x-data="{ open: false }"
     x-on:open-modal.window="$event.detail === '{{ $name }}' && (open = true)"
     x-on:close-modal.window="$event.detail === '{{ $name }}' && (open = false)"
     x-on:keydown.escape.window="open = false"
     class="relative z-50"
     role="dialog"
     aria-modal="true"
     x-show="open">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40"
         x-show="open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false">
    </div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
         x-show="open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95 translate-y-2">

        <div class="{{ $widthClass }} w-full bg-gray-900 border border-gray-700/60 rounded-2xl shadow-2xl"
             style="box-shadow: 0 25px 80px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.05);"
             @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700/50 bg-gray-900 rounded-t-2xl z-10 relative">
                <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
                <button @click="open = false"
                        class="p-1.5 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Content --}}
            <div class="p-6 overflow-y-auto max-h-[80vh] scrollbar-thin">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
