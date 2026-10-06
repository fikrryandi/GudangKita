<?php

$models = [
    'Departemen' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    protected \$table = 'departemen';
    protected \$guarded = ['id'];
}
EOT,
    'Gedung' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    protected \$table = 'gedung';
    protected \$guarded = ['id'];
}
EOT,
    'Kategori' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected \$table = 'kategori';
    protected \$guarded = ['id'];
}
EOT,
    'Supplier' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected \$table = 'supplier';
    protected \$guarded = ['id'];
}
EOT,
    'Barang' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected \$table = 'barang';
    protected \$guarded = ['id'];

    public function kategori() { return \$this->belongsTo(Kategori::class); }
    public function departemen() { return \$this->belongsTo(Departemen::class); }
}
EOT,
    'Stok' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected \$table = 'stok';
    protected \$guarded = ['id'];

    public function barang() { return \$this->belongsTo(Barang::class); }
    public function departemen() { return \$this->belongsTo(Departemen::class); }
    public function gedung() { return \$this->belongsTo(Gedung::class); }
}
EOT,
    'BarangMasuk' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected \$table = 'barang_masuk';
    protected \$guarded = ['id'];

    public function departemen() { return \$this->belongsTo(Departemen::class); }
    public function gedung() { return \$this->belongsTo(Gedung::class); }
    public function supplier() { return \$this->belongsTo(Supplier::class); }
    public function user() { return \$this->belongsTo(User::class); }
    public function details() { return \$this->hasMany(BarangMasukDetail::class); }
}
EOT,
    'BarangMasukDetail' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasukDetail extends Model
{
    protected \$table = 'barang_masuk_detail';
    protected \$guarded = ['id'];

    public function barangMasuk() { return \$this->belongsTo(BarangMasuk::class); }
    public function barang() { return \$this->belongsTo(Barang::class); }
}
EOT,
    'RequestBarang' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestBarang extends Model
{
    protected \$table = 'request_barang';
    protected \$guarded = ['id'];

    public function departemen() { return \$this->belongsTo(Departemen::class); }
    public function gedung() { return \$this->belongsTo(Gedung::class); }
    public function peminta() { return \$this->belongsTo(User::class, 'peminta_user_id'); }
    public function approver() { return \$this->belongsTo(User::class, 'approver_id'); }
    public function details() { return \$this->hasMany(RequestBarangDetail::class); }
}
EOT,
    'RequestBarangDetail' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestBarangDetail extends Model
{
    protected \$table = 'request_barang_detail';
    protected \$guarded = ['id'];

    public function requestBarang() { return \$this->belongsTo(RequestBarang::class); }
    public function barang() { return \$this->belongsTo(Barang::class); }
}
EOT,
    'StockMovement' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected \$table = 'stock_movement';
    protected \$guarded = ['id'];

    public function barang() { return \$this->belongsTo(Barang::class); }
    public function departemen() { return \$this->belongsTo(Departemen::class); }
    public function gedung() { return \$this->belongsTo(Gedung::class); }
    public function user() { return \$this->belongsTo(User::class); }
}
EOT,
    'Transfer' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected \$table = 'transfer';
    protected \$guarded = ['id'];

    public function departemenAsal() { return \$this->belongsTo(Departemen::class, 'departemen_asal_id'); }
    public function gedungAsal() { return \$this->belongsTo(Gedung::class, 'gedung_asal_id'); }
    public function departemenTujuan() { return \$this->belongsTo(Departemen::class, 'departemen_tujuan_id'); }
    public function gedungTujuan() { return \$this->belongsTo(Gedung::class, 'gedung_tujuan_id'); }
    public function barang() { return \$this->belongsTo(Barang::class); }
    public function user() { return \$this->belongsTo(User::class); }
}
EOT,
    'Adjustment' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected \$table = 'adjustment';
    protected \$guarded = ['id'];

    public function barang() { return \$this->belongsTo(Barang::class); }
    public function departemen() { return \$this->belongsTo(Departemen::class); }
    public function gedung() { return \$this->belongsTo(Gedung::class); }
    public function user() { return \$this->belongsTo(User::class); }
}
EOT,
    'StockOpname' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected \$table = 'stock_opname';
    protected \$guarded = ['id'];

    public function departemen() { return \$this->belongsTo(Departemen::class); }
    public function gedung() { return \$this->belongsTo(Gedung::class); }
    public function user() { return \$this->belongsTo(User::class); }
    public function details() { return \$this->hasMany(StockOpnameDetail::class); }
}
EOT,
    'StockOpnameDetail' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected \$table = 'stock_opname_detail';
    protected \$guarded = ['id'];

    public function stockOpname() { return \$this->belongsTo(StockOpname::class); }
    public function barang() { return \$this->belongsTo(Barang::class); }
}
EOT,
    'Nota' => <<<EOT
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected \$table = 'nota';
    protected \$guarded = ['id'];

    public function requestBarang() { return \$this->belongsTo(RequestBarang::class); }
    public function dicetakOleh() { return \$this->belongsTo(User::class, 'dicetak_oleh'); }
}
EOT,
];

foreach ($models as $name => $content) {
    file_put_contents(__DIR__ . "/app/Models/{$name}.php", $content);
}
echo "Models generated successfully.\n";
