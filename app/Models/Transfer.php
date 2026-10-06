<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table = 'transfer';
    protected $guarded = ['id'];

    public function departemenAsal() { return $this->belongsTo(Departemen::class, 'departemen_asal_id'); }
    public function gedungAsal() { return $this->belongsTo(Gedung::class, 'gedung_asal_id'); }
    public function departemenTujuan() { return $this->belongsTo(Departemen::class, 'departemen_tujuan_id'); }
    public function gedungTujuan() { return $this->belongsTo(Gedung::class, 'gedung_tujuan_id'); }
    public function barang() { return $this->belongsTo(Barang::class); }
    public function user() { return $this->belongsTo(User::class); }
}