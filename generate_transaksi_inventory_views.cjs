const fs = require('fs');
const path = require('path');

const entities = [
    // TRANSAKSI
    {
        folder: 'transaksi/barang-masuk',
        views: ['index', 'create', 'show'],
        title: 'Barang Masuk',
        routePrefix: 'transaksi.barang-masuk'
    },
    {
        folder: 'transaksi/request-barang',
        views: ['index', 'create', 'show'],
        title: 'Barang Keluar (Request)',
        routePrefix: 'transaksi.request'
    },
    {
        folder: 'transaksi/approval',
        views: ['index', 'show'],
        title: 'Approval Barang Keluar',
        routePrefix: 'transaksi.approval'
    },
    {
        folder: 'transaksi/transfer',
        views: ['index', 'create'],
        title: 'Transfer Barang',
        routePrefix: 'transaksi.transfer'
    },
    {
        folder: 'transaksi/adjustment',
        views: ['index', 'create'],
        title: 'Penyesuaian Stok (Adjustment)',
        routePrefix: 'transaksi.adjustment'
    },
    {
        folder: 'transaksi/nota',
        views: ['index', 'show', 'cetak'],
        title: 'Nota Transaksi',
        routePrefix: 'transaksi.nota'
    },
    // INVENTORY
    {
        folder: 'inventory/stok',
        views: ['index'],
        title: 'Stok Barang',
        routePrefix: 'inventory.stok'
    },
    {
        folder: 'inventory/kartu-stok',
        views: ['index'],
        title: 'Kartu Stok',
        routePrefix: 'inventory.kartu-stok'
    },
    {
        folder: 'inventory/stock-opname',
        views: ['index'],
        title: 'Stock Opname',
        routePrefix: 'inventory.stock-opname'
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
        } else if (view === 'show') {
            content = `<x-app-layout>
    <x-slot name="title">Detail ${entity.title}</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('${entity.routePrefix}.index') }}" class="p-2 bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg border border-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-2xl font-bold text-white tracking-tight">Detail ${entity.title}</h2>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl overflow-hidden p-6">
            <p class="text-gray-400">Detail dari data ${entity.title.toLowerCase()} ini akan ditampilkan di sini.</p>
        </div>
    </div>
</x-app-layout>`;
        } else if (view === 'cetak') {
            content = `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Nota - ${entity.title}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>GudangKita</h1>
        <p>Cetak ${entity.title}</p>
    </div>
    <p>Ini adalah format nota yang akan dicetak sebagai PDF / Print view.</p>
</body>
</html>`;
        }

        fs.writeFileSync(path.join(dir, view + '.blade.php'), content);
    });
    console.log("Generated views for " + entity.title);
});
