<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';
    protected $guarded = ['id'];

    public function departemen() { return $this->belongsTo(Departemen::class); }
    public function gedung() { return $this->belongsTo(Gedung::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function details() { return $this->hasMany(BarangMasukDetail::class); }
}