<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'invoice_folio',
        'total_fees',
        'total_iva',
        'grand_total',
        'sat_payment',
        'released_balance',
        'liquidated_records_count',
    ];

    /**
     * Una factura puede estar agrupando múltiples trámites.
     */
    public function entries()
    {
        return $this->hasMany(FinanceEntry::class);
    }
}