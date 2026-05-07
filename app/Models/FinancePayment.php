<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'finance_entry_id',
        'date',
        'amount',
        'receipt_number',
    ];

    /**
     * Todo pago le pertenece a un único trámite.
     */
    public function entry()
    {
        return $this->belongsTo(FinanceEntry::class, 'finance_entry_id');
    }
}