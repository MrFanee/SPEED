<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PO extends Model
{
    protected $primaryKey = 'id_po';
    protected $fillable = ['po_number', 'qty_po', 'qty_outstanding', 'status', 'id_vendor', 'id_part'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part');
    }
}
