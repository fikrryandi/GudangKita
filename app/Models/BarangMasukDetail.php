<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasukDetail extends Model
{
    protected $table = 'barang_masuk_detail';
    protected $guarded = ['id'];

    public function barangMasuk() { return $this->belongsTo(BarangMasuk::class); }
    public function barang() { return $this->belongsTo(Barang::class); }
}