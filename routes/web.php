<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterData\BarangController;
use App\Http\Controllers\MasterData\KategoriController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\MasterData\DepartemenController;
use App\Http\Controllers\MasterData\GedungController;
use App\Http\Controllers\Transaksi\BarangMasukController;
use App\Http\Controllers\Transaksi\RequestBarangController;
use App\Http\Controllers\Transaksi\ApprovalController;
use App\Http\Controllers\Transaksi\TransferController;
use App\Http\Controllers\Transaksi\AdjustmentController;
use App\Http\Controllers\Transaksi\NotaController;
use App\Http\Controllers\Inventory\StokController;
use App\Http\Controllers\Inventory\KartuStokController;
use App\Http\Controllers\Inventory\StockOpnameController;

/*
|--------------------------------------------------------------------------
| Web Routes — GudangKita
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============ PROFILE ============
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============ MASTER DATA (Admin & Super Admin) ============
    Route::prefix('master')->name('master.')->middleware('role:Super Admin|Admin HRGA|Admin EHS|Admin MTC')->group(function () {
        Route::resource('barang',     BarangController::class);
        Route::resource('kategori',   KategoriController::class);
        Route::resource('supplier',   SupplierController::class);
        Route::resource('departemen', DepartemenController::class);
        Route::resource('gedung',     GedungController::class);
    });

    // JSON endpoint for Alpine.js – any auth user
    Route::get('/api/barang', [BarangController::class, 'apiAll'])->name('api.barang.all');
    Route::get('/api/barang-by-departemen/{departemenId}', [BarangController::class, 'byDepartemen'])->name('api.barang.by-departemen');
    Route::get('/api/stok-tersedia', [RequestBarangController::class, 'stokTersedia'])->name('api.stok.tersedia');

    // ============ TRANSAKSI ============
    Route::prefix('transaksi')->name('transaksi.')->group(function () {

        // Barang Masuk (Admin + Super Admin)
        Route::middleware('role:Super Admin|Admin HRGA|Admin EHS|Admin MTC')->group(function () {
            Route::resource('barang-masuk', BarangMasukController::class)->only(['index','create','store','show']);
        });

        // Request Barang (Karyawan)
        Route::prefix('request')->name('request.')->group(function () {
            Route::get('/',         [RequestBarangController::class, 'index'])->name('index');
            Route::get('/create',   [RequestBarangController::class, 'create'])->name('create');
            Route::post('/',        [RequestBarangController::class, 'store'])->name('store');
            Route::get('/{requestBarang}', [RequestBarangController::class, 'show'])->name('show');
        });

        // Approval (Admin + Super Admin)
        Route::prefix('approval')->name('approval.')->middleware('role:Super Admin|Admin HRGA|Admin EHS|Admin MTC')->group(function () {
            Route::get('/',               [ApprovalController::class, 'index'])->name('index');
            Route::get('/{requestBarang}', [ApprovalController::class, 'show'])->name('show');
            Route::post('/{requestBarang}/approve', [ApprovalController::class, 'approve'])->name('approve');
            Route::post('/{requestBarang}/reject',  [ApprovalController::class, 'reject'])->name('reject');
            Route::post('/{requestBarang}/selesai', [ApprovalController::class, 'selesai'])->name('selesai');
        });

        // Transfer (Admin + Super Admin)
        Route::middleware('role:Super Admin|Admin HRGA|Admin EHS|Admin MTC')->group(function () {
            Route::resource('transfer', TransferController::class)->only(['index','create','store']);
        });

        // Adjustment (Admin + Super Admin)
        Route::middleware('role:Super Admin|Admin HRGA|Admin EHS|Admin MTC')->group(function () {
            Route::resource('adjustment', AdjustmentController::class)->only(['index','create','store']);
        });

        // Nota (Admin + Super Admin)
        Route::prefix('nota')->name('nota.')->middleware('role:Super Admin|Admin HRGA|Admin EHS|Admin MTC')->group(function () {
            Route::get('/',               [NotaController::class, 'index'])->name('index');
            Route::get('/{requestBarang}', [NotaController::class, 'show'])->name('show');
            Route::get('/{requestBarang}/cetak', [NotaController::class, 'cetak'])->name('cetak');
        });
    });

    // ============ INVENTORY ============
    Route::prefix('inventory')->name('inventory.')->middleware('role:Super Admin|Admin HRGA|Admin EHS|Admin MTC')->group(function () {
        Route::get('/stok',           [StokController::class, 'index'])->name('stok.index');
        Route::get('/kartu-stok',     [KartuStokController::class, 'index'])->name('kartu-stok.index');
        Route::get('/stock-opname',   [StockOpnameController::class, 'index'])->name('stock-opname.index');
        Route::post('/stock-opname',  [StockOpnameController::class, 'store'])->name('stock-opname.store');
        Route::post('/stock-opname/{stockOpname}/apply', [StockOpnameController::class, 'apply'])->name('stock-opname.apply');
    });

    // ============ LAPORAN ============
    Route::prefix('laporan')->name('laporan.')->middleware('role:Super Admin|Admin HRGA|Admin EHS|Admin MTC')->group(function () {
        Route::get('/',             [LaporanController::class, 'index'])->name('index');
        Route::get('/barang-masuk', [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
        Route::get('/barang-keluar', [LaporanController::class, 'barangKeluar'])->name('barang-keluar');
        Route::get('/stok',         [LaporanController::class, 'stok'])->name('stok');
        Route::get('/nota',         [LaporanController::class, 'nota'])->name('nota');
    });

    // ============ NOTIFIKASI (all) ============
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/',                     [NotifikasiController::class, 'index'])->name('index');
        Route::get('/mark-all-read',        [NotifikasiController::class, 'markAllRead'])->name('mark-all-read');
        Route::get('/{id}',                 [NotifikasiController::class, 'show'])->name('show');
        Route::delete('/{id}',              [NotifikasiController::class, 'destroy'])->name('destroy');
    });

    // ============ USER MANAGEMENT (Super Admin) ============
    Route::prefix('user-management')->name('user-management.')->middleware('role:Super Admin')->group(function () {
        Route::resource('user', UserController::class);
        Route::get('role/{role}/permissions', [RoleController::class, 'getPermissions'])->name('role.permissions');
        Route::resource('role', RoleController::class)->only(['index','store','update','destroy']);
    });

    // ============ ACTIVITY LOG (Super Admin) ============
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index')->middleware('role:Super Admin');

    // ============ SETTINGS (Super Admin) ============
    Route::prefix('settings')->name('settings.')->middleware('role:Super Admin')->group(function () {
        Route::get('/',  [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');
    });
});

require __DIR__.'/auth.php';
