<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'po_no',
        'po_date',
        'part_no',
        'description',
        'unit',
        'qty',
        'weight',
        'total_weight',
        'delivery_date',
        'drawing_attachment',
    ];
    
    public function party()
    {
        return $this->belongsTo(Party::class, 'customer_name');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'part_no');
    }
}
