<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CompanyUser extends Authenticatable
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
        return $this->belongsTo(Company::class);
    }
}
