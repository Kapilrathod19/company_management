<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Party extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'name',
        'address',
        'email',
        'password',
        'mobile_number',
        'gst_number',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
