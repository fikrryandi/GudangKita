<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $table = 'stock_opname';
    protected $guarded = ['id'];

    public function departemen() { return $this->belongsTo(Departemen::class); }
    public function gedung() { return $this->belongsTo(Gedung::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function details() { return $this->hasMany(StockOpnameDetail::class); }
}