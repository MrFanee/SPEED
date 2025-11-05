<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'master_stock';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rm',
        'wip',
        'fg',
        'judgement',
        'kategori_problem',
        'detail_problem',
        'vendor_id',
        'part_id'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($stock) {
            $qty_po = $stock->part->po->last()->qty_po ?? 0;
            $std_stock = $stock->part->std_stock ?? 0;
            $fg = $stock->fg ?? 0;

            if ($qty_po > 0 && $fg >= $std_stock) {
                $stock->judgement = 'OK';
            } elseif ($qty_po > 0 && $fg < $std_stock) {
                $stock->judgement = 'NG';
            } elseif ($qty_po <= 0) {
                $stock->judgement = 'NO PO';
            } else {
                $stock->judgement = '-';
            }
        });
    }
}
