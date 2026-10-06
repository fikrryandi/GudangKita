<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';
    protected $guarded = ['id'];

    public function barang() { return $this->belongsTo(Barang::class); }
    public function departemen() { return $this->belongsTo(Departemen::class); }
    public function gedung() { return $this->belongsTo(Gedung::class); }
}