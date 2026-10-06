<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestBarang extends Model
{
    protected $table = 'request_barang';
    protected $guarded = ['id'];

    public function departemen() { return $this->belongsTo(Departemen::class); }
    public function gedung() { return $this->belongsTo(Gedung::class); }
    public function peminta() { return $this->belongsTo(User::class, 'peminta_user_id'); }
    public function approver() { return $this->belongsTo(User::class, 'approver_id'); }
    public function details() { return $this->hasMany(RequestBarangDetail::class); }
}