{{-- Page Header Component --}}
@props([
    'title' => '',
    'subtitle' => null,
])

<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-heading font-bold text-white">{{ $title }}</h1>
            @if($subtitle)
            <p class="text-sm text-gray-400 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if(isset($actions))
        <div class="flex items-center gap-2 flex-wrap">
            {{ $actions }}
        </div>
        @endif
    </div>
</div>
