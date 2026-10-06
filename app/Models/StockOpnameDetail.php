<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected $table = 'stock_opname_detail';
    protected $guarded = ['id'];

    public function stockOpname() { return $this->belongsTo(StockOpname::class); }
    public function barang() { return $this->belongsTo(Barang::class); }
}