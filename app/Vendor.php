<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $primaryKey = 'id_vendor';
    protected $fillable = ['nickname', 'vendor_name', 'alamat', 'id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function poTables()
    {
        return $this->hasMany(PO::class, 'id_vendor');
    }

    public function masterStocks()
    {
        return $this->hasMany(Stock::class, 'id_vendor');
    }
}
