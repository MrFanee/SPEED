<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DI extends Model
{
    protected $table = 'master_di';
    protected $primaryKey = 'id';
    protected $fillable = ['qty_plan', 'qty_delivery', 'balance', 'po_id'];

    public function poTables()
    {
        return $this->belongsTo(PO::class, 'po_id');
    }
}
