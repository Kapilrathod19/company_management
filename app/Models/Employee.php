<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contractor_name',
        'emp_no',
        'employee_name',
        'designation',
        'contact_no',
        'certificate',
        'status',
    ];
}
