<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'master_stock';
    protected $primaryKey = 'id';
    protected $fillable = ['rm', 'wip', 'fg', 'judgement', 'kategori_problem', 'detail_problem', 
    'vendor_id', 'part_id'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }
}
