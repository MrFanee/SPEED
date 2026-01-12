<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DI extends Model
{
    protected $table = 'master_di';
    protected $primaryKey = 'id';
    protected $fillable = ['delivery_date', 'qty_plan', 'qty_delivery', 'po_id', 'part_id', 'balance', 'qty_delay', 'qty_manifest'];

    public function po()
    {
        return $this->belongsTo(PO::class, 'po_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($di) {
            $di->balance = $di->qty_delivery - $di->qty_plan;
            $di->qty_delay = $di->qty_plan - $di->qty_delivery;
        });
    }
}
