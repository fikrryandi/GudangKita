<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'nota';
    protected $guarded = ['id'];

    public function requestBarang() { return $this->belongsTo(RequestBarang::class); }
    public function dicetakOleh() { return $this->belongsTo(User::class, 'dicetak_oleh'); }
}