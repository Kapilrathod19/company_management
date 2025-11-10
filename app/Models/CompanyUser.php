<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'mobile',
        'password',
        'department'
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
