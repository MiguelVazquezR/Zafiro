<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    const IN_PROCESS = 0;
    const DISPATCHED = 1;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'service',
        'message',
        'status',
    ];
}
