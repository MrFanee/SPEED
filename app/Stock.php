<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $primaryKey = 'id_stock';
    protected $fillable = ['rm', 'wip', 'fg', 'judgement', 'kategori_problem', 'detail_problem', 
    'id_vendor', 'id_part'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part');
    }
}
