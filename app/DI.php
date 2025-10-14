<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DI extends Model
{
    protected $primaryKey = 'id_di';
    protected $fillable = ['qty_plan', 'qty_delivery', 'balance', 'id_po'];

    public function poTables()
    {
        return $this->belongsTo(PO::class, 'id_po');
    }
}
