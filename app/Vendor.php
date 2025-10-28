<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $fillable = ['nickname', 'vendor_name', 'alamat', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function po()
    {
        return $this->hasMany(PO::class, 'vendor_id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'vendor_id');
    }
}
