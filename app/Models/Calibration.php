<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calibration extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date',
        'machine_no',
        'machine_name',
        'calibration_date',
        'certificate',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_name');
    }
}
