<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $guarded = ['id'];

    public function kategori() { return $this->belongsTo(Kategori::class); }
    public function departemen() { return $this->belongsTo(Departemen::class); }
}