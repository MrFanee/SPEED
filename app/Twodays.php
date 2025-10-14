<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Twodays extends Model
{
    protected $primaryKey = 'id_2hk';
    protected $fillable = ['std_stock', 'id_part'];

    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part');
    }
}
