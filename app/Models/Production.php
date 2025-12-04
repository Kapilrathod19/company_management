<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'sr_no', 'date', 'employee_name', 'unit_no', 'component_no', 'description', 'process', 'qty', 'weight', 'total_weight', 'remark'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_name', 'id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'unit_no', 'id');
    }

    public function processDetails()
    {
        return $this->belongsTo(ProcessMaster::class, 'process', 'id');
    }
}
