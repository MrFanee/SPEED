<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DI extends Model
{
    protected $table = 'master_di';
    protected $primaryKey = 'id';
    protected $fillable = ['qty_plan', 'qty_delivery', 'po_id'];

    public function po()
    {
        return $this->belongsTo(PO::class, 'po_id');
    }

    public function getBalanceAttribute()
    {
        return $this->qty_delivery - $this->qty_plan;
    }
}
