<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestBarangDetail extends Model
{
    protected $table = 'request_barang_detail';
    protected $guarded = ['id'];

    public function requestBarang() { return $this->belongsTo(RequestBarang::class); }
    public function barang() { return $this->belongsTo(Barang::class); }
}