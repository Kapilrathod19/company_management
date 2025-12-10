<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWork extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'date',
        'employee_id',
        'work_done',
        'weight',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
