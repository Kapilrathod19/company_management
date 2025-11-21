<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'process_id', 'position'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function processMaster()
    {
        return $this->belongsTo(ProcessMaster::class, 'process_id');
    }
}
