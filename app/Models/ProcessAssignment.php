<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessAssignment extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'component_no',
        'unit_no',
        'process_master_id',
        'employee_id',
        'process_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}
