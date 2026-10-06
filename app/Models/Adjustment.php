<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected $table = 'adjustment';
    protected $guarded = ['id'];

    public function barang() { return $this->belongsTo(Barang::class); }
    public function departemen() { return $this->belongsTo(Departemen::class); }
    public function gedung() { return $this->belongsTo(Gedung::class); }
    public function user() { return $this->belongsTo(User::class); }
}