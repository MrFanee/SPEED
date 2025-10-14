<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $primaryKey = 'id_part';
    protected $fillable = ['part_name', 'part_number', 'item_code'];
    
    public function poTables()  {
        return $this->hasMany(PO::class, 'id_part');
    }

    public function masterStocks() {
        return $this->hasMany(Stock::class, 'id_part');
    }

    public function twoDays() {
        return $this->hasMany(Twodays::class, 'id_part');
    }
}
