<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'concept',
        'amount',
        'is_invoiced',
    ];

    // Aseguramos que Eloquent trate este campo como un booleano (true/false) en lugar de entero (1/0)
    protected $casts = [
        'is_invoiced' => 'boolean',
    ];
}