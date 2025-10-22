<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Twodays extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['std_stock', 'part_id'];

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }
}
