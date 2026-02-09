<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDocument extends Model
{
    use HasFactory;

    protected $table = 'sales_order_documents';

    protected $fillable = [
        'user_id',
        'sales_order_id',
        'title',
        'document',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
}
