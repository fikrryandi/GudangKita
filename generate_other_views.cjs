const fs = require('fs');
const path = require('path');

const entities = [
    // LAPORAN
    {
        folder: 'laporan',
        views: ['index', 'barang-masuk', 'barang-keluar', 'stok', 'nota'],
        title: 'Laporan',
        routePrefix: 'laporan'
    },
    // USER MANAGEMENT
    {
        folder: 'user-management/user',
        views: ['index', 'create', 'edit'],
        title: 'User Management',
        routePrefix: 'user-management.user'
    },
    // ACTIVITY LOG
    {
        folder: 'activity-log',
        views: ['index'],
        title: 'Activity Log',
        routePrefix: 'activity-log'
    },
    // SETTINGS
    {
        folder: 'settings',
        views: ['index'],
        title: 'Pengaturan Sistem',
        routePrefix: 'settings'
    },
    // NOTIFIKASI
    {
        folder: 'notifikasi',
        views: ['index', 'show'],
        title: 'Notifikasi',
        routePrefix: 'notifikasi'
    }
];

const basePath = path.join(__dirname, 'resources', 'views');

entities.forEach(entity => {
    const dir = path.join(basePath, entity.folder);
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });

    entity.views.forEach(view => {
        let content = '';

        if (view === 'index') {
            content = `<x-app-layout>
    <x-slot name="title">${entity.title}</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white tracking-tight">${entity.title}</h2>
        ${entity.views.includes('create') ? `<a href="{{ route('${entity.routePrefix}.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)]">
            <i data-lucide="plus" class="w-4 h-4 inline-block mr-1"></i> Tambah Baru
        </a>` : ''}
    </div>

    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden p-6">
        <p class="text-gray-400">Halaman daftar ${entity.title.toLowerCase()} akan ditampilkan di sini.</p>
    </div>
</x-app-layout>`;
        } else if (view === 'create') {
            content = `<x-app-layout>
    <x-slot name="title">Tambah ${entity.title}</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('${entity.routePrefix}.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-2xl font-bold text-white tracking-tight">Tambah ${entity.title}</h2>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden p-6">
            <p class="text-gray-400">Form untuk menambah ${entity.title.toLowerCase()} akan ditampilkan di sini.</p>
        </div>
    </div>
</x-app-layout>`;
        } else if (view === 'edit') {
            content = `<x-app-layout>
    <x-slot name="title">Edit ${entity.title}</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('${entity.routePrefix}.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-2xl font-bold text-white tracking-tight">Edit ${entity.title}</h2>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden p-6">
            <p class="text-gray-400">Form untuk edit ${entity.title.toLowerCase()} akan ditampilkan di sini.</p>
        </div>
    </div>
</x-app-layout>`;
        } else {
            // For other views like laporan specific or show
            content = `<x-app-layout>
    <x-slot name="title">${entity.title} - ${view}</x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('${entity.routePrefix}.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-2xl font-bold text-white tracking-tight">${entity.title} - ${view}</h2>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden p-6">
            <p class="text-gray-400">Halaman ini adalah: ${view}.</p>
        </div>
    </div>
</x-app-layout>`;
        }

        fs.writeFileSync(path.join(dir, view + '.blade.php'), content);
    });
    console.log("Generated views for " + entity.title);
});
