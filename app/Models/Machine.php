<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category',
        'machine_no',
        'machine_name',
        'calibration_date',
        'remark',
    ];
}
