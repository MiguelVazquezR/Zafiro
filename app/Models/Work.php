<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'town',
        'ejido',
        'parcel_number',
        'customer_name',
        'price',
        'area',
        'description',
        'payments',
        'work_type',
        'start_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'payments' => 'array',
    ];
    
}
