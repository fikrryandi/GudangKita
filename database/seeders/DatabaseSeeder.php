<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Departemen;
use App\Models\Gedung;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Stok;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departemen
        $hrga = Departemen::create(['kode' => 'HRGA', 'nama' => 'Human Resources & General Affairs']);
        $ehs = Departemen::create(['kode' => 'EHS', 'nama' => 'Environment, Health, and Safety']);
        $mtc = Departemen::create(['kode' => 'MTC', 'nama' => 'Maintenance']);

        // 2. Gedung
        $gedung1 = Gedung::create(['nama' => 'Gedung 1']);
        $gedung2 = Gedung::create(['nama' => 'Gedung 2']);

        // 3. Kategori
        $katAtk = Kategori::create(['kode_prefix' => 'ATK', 'nama' => 'Alat Tulis Kantor']);
        $katApd = Kategori::create(['kode_prefix' => 'APD', 'nama' => 'Alat Pelindung Diri']);
        $katSpr = Kategori::create(['kode_prefix' => 'SPR', 'nama' => 'Sparepart']);
        $katMtn = Kategori::create(['kode_prefix' => 'MTN', 'nama' => 'Maintenance']);
        $katTls = Kategori::create(['kode_prefix' => 'TLS', 'nama' => 'Tools']);

        // 4. Permissions & Roles
        $permissions = [
            'view users', 'create users', 'edit users', 'delete users',
            'view roles', 'create roles', 'edit roles', 'delete roles',
            'view master_data', 'create master_data', 'edit master_data', 'delete master_data',
            'view inventory', 'create inventory', 'edit inventory', 'delete inventory',
            'view transaksi', 'create transaksi', 'edit transaksi', 'delete transaksi',
            'view laporan',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $roleSuperAdmin->syncPermissions(Permission::all());

        $roleAdminHRGA = Role::firstOrCreate(['name' => 'Admin HRGA']);
        $roleAdminEHS = Role::firstOrCreate(['name' => 'Admin EHS']);
        $roleAdminMTC = Role::firstOrCreate(['name' => 'Admin MTC']);
        $roleKaryawan = Role::firstOrCreate(['name' => 'Karyawan']);

        // 5. Users
        $admin = User::firstOrCreate(
            ['username' => 'administrator'],
            [
                'nama_lengkap' => 'Administrator',
                'email' => 'admin@gudangkita.com',
                'password' => Hash::make('password'),
                'departemen_id' => null,
                'status' => true,
            ]
        );
        $admin->assignRole($roleSuperAdmin);

        $adminHrga = User::create([
            'username' => 'adminhrga',
            'nama_lengkap' => 'Rina Wulandari',
            'email' => 'rina@gudangkita.com',
            'password' => Hash::make('password'),
            'departemen_id' => $hrga->id,
            'status' => true,
        ]);
        $adminHrga->assignRole($roleAdminHRGA);

        $adminEhs = User::create([
            'username' => 'adminehs',
            'nama_lengkap' => 'Admin EHS',
            'email' => 'ehs@gudangkita.com',
            'password' => Hash::make('password'),
            'departemen_id' => $ehs->id,
            'status' => true,
        ]);
        $adminEhs->assignRole($roleAdminEHS);

        $adminMtc = User::create([
            'username' => 'adminmtc',
            'nama_lengkap' => 'Admin MTC',
            'email' => 'mtc@gudangkita.com',
            'password' => Hash::make('password'),
            'departemen_id' => $mtc->id,
            'status' => true,
        ]);
        $adminMtc->assignRole($roleAdminMTC);

        $budi = User::create([
            'username' => 'budisantoso',
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi@gudangkita.com',
            'password' => Hash::make('password'),
            'departemen_id' => $hrga->id,
            'status' => true,
        ]);
        $budi->assignRole($roleKaryawan);

        $siti = User::create([
            'username' => 'sitiamelia',
            'nama_lengkap' => 'Siti Amelia',
            'email' => 'siti@gudangkita.com',
            'password' => Hash::make('password'),
            'departemen_id' => $ehs->id,
            'status' => true,
        ]);
        $siti->assignRole($roleKaryawan);

        // 6. Barang
        $atk001 = Barang::create(['kode_barang' => 'ATK-001', 'nama' => 'Kertas A4', 'kategori_id' => $katAtk->id, 'departemen_id' => $hrga->id, 'satuan' => 'Rim', 'harga_satuan' => 45000, 'minimum_stock' => 5]);
        $atk002 = Barang::create(['kode_barang' => 'ATK-002', 'nama' => 'Pulpen', 'kategori_id' => $katAtk->id, 'departemen_id' => $hrga->id, 'satuan' => 'Pcs', 'harga_satuan' => 3500, 'minimum_stock' => 10]);
        $atk003 = Barang::create(['kode_barang' => 'ATK-003', 'nama' => 'Toner Printer', 'kategori_id' => $katAtk->id, 'departemen_id' => $hrga->id, 'satuan' => 'Unit', 'harga_satuan' => 650000, 'minimum_stock' => 2]);
        
        $apd001 = Barang::create(['kode_barang' => 'APD-001', 'nama' => 'Safety Helmet', 'kategori_id' => $katApd->id, 'departemen_id' => $ehs->id, 'satuan' => 'Pcs', 'harga_satuan' => 150000, 'minimum_stock' => 10]);
        $apd002 = Barang::create(['kode_barang' => 'APD-002', 'nama' => 'Safety Shoes', 'kategori_id' => $katApd->id, 'departemen_id' => $ehs->id, 'satuan' => 'Pasang', 'harga_satuan' => 250000, 'minimum_stock' => 10]);
        $apd003 = Barang::create(['kode_barang' => 'APD-003', 'nama' => 'Safety Vest', 'kategori_id' => $katApd->id, 'departemen_id' => $ehs->id, 'satuan' => 'Pcs', 'harga_satuan' => 85000, 'minimum_stock' => 10]);

        $spr001 = Barang::create(['kode_barang' => 'SPR-001', 'nama' => 'Bearing', 'kategori_id' => $katSpr->id, 'departemen_id' => $mtc->id, 'satuan' => 'Pcs', 'harga_satuan' => 175000, 'minimum_stock' => 5]);
        $spr002 = Barang::create(['kode_barang' => 'SPR-002', 'nama' => 'Oli Mesin', 'kategori_id' => $katMtn->id, 'departemen_id' => $mtc->id, 'satuan' => 'Liter', 'harga_satuan' => 60000, 'minimum_stock' => 20]);
        $tls001 = Barang::create(['kode_barang' => 'TLS-001', 'nama' => 'Kunci Inggris', 'kategori_id' => $katTls->id, 'departemen_id' => $mtc->id, 'satuan' => 'Pcs', 'harga_satuan' => 95000, 'minimum_stock' => 2]);

        // 7. Stok Awal
        Stok::create(['barang_id' => $atk001->id, 'departemen_id' => $hrga->id, 'gedung_id' => $gedung1->id, 'qty' => 20]);
        
        Stok::create(['barang_id' => $apd001->id, 'departemen_id' => $ehs->id, 'gedung_id' => $gedung1->id, 'qty' => 50]);
        Stok::create(['barang_id' => $apd001->id, 'departemen_id' => $ehs->id, 'gedung_id' => $gedung2->id, 'qty' => 20]);
        
        Stok::create(['barang_id' => $apd002->id, 'departemen_id' => $ehs->id, 'gedung_id' => $gedung2->id, 'qty' => 5]);
        
        Stok::create(['barang_id' => $spr001->id, 'departemen_id' => $mtc->id, 'gedung_id' => $gedung1->id, 'qty' => 0]);
    }
}
