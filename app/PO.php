<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PO extends Model
{
    protected $table = 'po_table';
    protected $primaryKey = 'id';
    protected $fillable = ['po_number', 'qty_po', 'qty_outstanding', 'status', 'vendor_id', 'part_id'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }
}
