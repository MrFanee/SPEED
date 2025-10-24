<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $table = 'parts';
    protected $primaryKey = 'id';
    protected $fillable = ['part_name', 'part_number', 'item_code'];

    public function poTables()
    {
        return $this->hasMany(PO::class, 'part_id');
    }

    public function masterStocks()
    {
        return $this->hasMany(Stock::class, 'part_id');
    }

    public function twoDays()
    {
        return $this->hasMany(Twodays::class, 'part_id');
    }
}
