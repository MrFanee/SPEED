<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $table = 'parts';
    protected $primaryKey = 'id';
    protected $fillable = ['part_name', 'part_number', 'item_code'];

    public function po()
    {
        return $this->hasMany(PO::class, 'part_id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'part_id');
    }

    public function twoDays()
    {
        return $this->hasMany(Twodays::class, 'part_id');
    }
}
