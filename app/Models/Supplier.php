<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_name',
        'po_no',
        'po_date',
        'customer_name',
        'sales_po_no',
        'unit_no',
        'part_no',
        'description',
        'qty',
        'weight',
        'total_weight',
        'remark',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class, 'supplier_name');
    }
    
    public function sales_order()
    {
        return $this->belongsTo(Party::class, 'customer_name');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'part_no');
    }

}
