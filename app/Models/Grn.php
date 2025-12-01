<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grn_no',
        'grn_date',
        'category',
        'party_name',
        'po_no',
        'party_challan_no',
        'party_challan_date',
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
        return $this->belongsTo(Party::class, 'party_name');
    }
}
