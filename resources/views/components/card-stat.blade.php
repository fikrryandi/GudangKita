{{-- Stat Card Component --}}
@props([
    'label' => '',
    'value' => 0,
    'icon' => 'box',
    'color' => 'cyan',   // cyan | violet | emerald | amber | red
    'trend' => null,     // optional: '+12%'
    'subtitle' => null,
])

@php
$colors = [
    'blue'    => ['bg' => 'bg-blue-500/10',    'icon' => 'text-blue-400',    'border' => 'border-blue-500/20',    'glow' => '0 0 20px rgba(59,130,246,0.15)'],
    'cyan'    => ['bg' => 'bg-cyan-500/10',    'icon' => 'text-cyan-400',    'border' => 'border-cyan-500/20',    'glow' => '0 0 20px rgba(34,211,238,0.15)'],
    'violet'  => ['bg' => 'bg-violet-500/10',  'icon' => 'text-violet-400',  'border' => 'border-violet-500/20',  'glow' => '0 0 20px rgba(167,139,250,0.15)'],
    'emerald' => ['bg' => 'bg-emerald-500/10', 'icon' => 'text-emerald-400', 'border' => 'border-emerald-500/20', 'glow' => '0 0 20px rgba(52,211,153,0.15)'],
    'amber'   => ['bg' => 'bg-amber-500/10',   'icon' => 'text-amber-400',   'border' => 'border-amber-500/20',   'glow' => '0 0 20px rgba(251,191,36,0.15)'],
    'red'     => ['bg' => 'bg-red-500/10',     'icon' => 'text-red-400',     'border' => 'border-red-500/20',     'glow' => '0 0 20px rgba(248,113,113,0.15)'],
];
$c = $colors[$color] ?? $colors['cyan'];
@endphp

<div class="relative group rounded-2xl border p-5 transition-all duration-300 hover:-translate-y-1 cursor-default overflow-hidden
            {{ $c['bg'] }} {{ $c['border'] }}"
     style="background: rgba(17,24,39,0.7); backdrop-filter: blur(12px); box-shadow: {{ $c['glow'] }}">
    
    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
         style="background: radial-gradient(circle at 50% 0%, rgba(255,255,255,0.03) 0%, transparent 70%)"></div>
    
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">{{ $label }}</p>
            <p class="mt-2 text-2xl font-heading font-bold text-white tabular-nums truncate">{{ $value }}</p>
            @if($subtitle)
            <p class="mt-1 text-xs text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 {{ $c['bg'] }} border {{ $c['border'] }}">
            <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $c['icon'] }}"></i>
        </div>
    </div>
    
    @if($trend)
    <div class="mt-3 flex items-center gap-1.5">
        <span class="text-xs font-medium {{ str_starts_with($trend, '+') ? 'text-emerald-400' : 'text-red-400' }}">{{ $trend }}</span>
        <span class="text-xs text-gray-500">dari bulan lalu</span>
    </div>
    @endif
</div>
