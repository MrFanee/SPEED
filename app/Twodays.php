<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Part;

class Twodays extends Model
{
    protected $table = 'master_2hk';
    protected $primaryKey = 'id';
    protected $fillable = ['std_stock', 'part_id'];

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }
}
