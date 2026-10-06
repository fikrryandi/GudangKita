{{-- Status Badge Component --}}
@props([
    'status' => 'Aman',  // Aman | Menipis | Habis | Menunggu Approval | Diproses | Ditolak | Selesai | Baik | Rusak
])

@php
$map = [
    'Aman'               => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
    'Menipis'            => 'bg-amber-500/15 text-amber-400 border-amber-500/30 animate-pulse',
    'Habis'              => 'bg-red-500/15 text-red-400 border-red-500/30',
    'Menunggu Approval'  => 'bg-yellow-500/15 text-yellow-400 border-yellow-500/30',
    'Diproses'           => 'bg-blue-500/15 text-blue-400 border-blue-500/30',
    'Ditolak'            => 'bg-red-500/15 text-red-400 border-red-500/30',
    'Selesai'            => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
    'Baik'               => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
    'Rusak'              => 'bg-red-500/15 text-red-400 border-red-500/30',
    'Draft'              => 'bg-gray-500/15 text-gray-400 border-gray-500/30',
    'Diterapkan'         => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30',
    'Aktif'              => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
    'Nonaktif'           => 'bg-gray-500/15 text-gray-400 border-gray-500/30',
];

$class = $map[$status] ?? 'bg-gray-500/15 text-gray-400 border-gray-500/30';
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $class }}">
    <span class="w-1.5 h-1.5 rounded-full bg-current {{ in_array($status, ['Menipis']) ? 'animate-pulse' : '' }}"></span>
    {{ $status }}
</span>
