<x-app-layout>
    <x-slot name="title">Activity Log</x-slot>

    <div class="flex justify-between items-center mb-6">
    </div>
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-700/50">
                    <tr><th class="px-5 py-4">Waktu</th><th class="px-5 py-4">Pengguna</th><th class="px-5 py-4">Aktivitas</th><th class="px-5 py-4">IP Address</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="px-5 py-3.5 text-gray-300">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3.5 font-medium text-white">{{ optional($log->causer)->nama_lengkap ?? 'Sistem' }}</td>
                        <td class="px-5 py-3.5 text-gray-300">{{ $log->description }}</td>
                        <td class="px-5 py-3.5 font-mono text-gray-400">{{ $log->properties['ip'] ?? '-' }}</td>
                    </tr>
                                        @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-gray-600"></i>
                            <p>Belum ada log aktivitas.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-700/50">{{ $logs->links() }}</div>
    </div>


</x-app-layout>