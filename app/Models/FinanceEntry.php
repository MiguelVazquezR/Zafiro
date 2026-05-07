<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'client_name',
        'phone',
        'service_type',
        'location',
        'original_amount',
        'iva_mode',
        'fees',
        'iva',
        'total_with_iva',
        'advance_payment',
        'balance',
        'status',
        'is_declared',
        'receipt_number',
        'finance_invoice_id',
    ];

    /**
     * Un trámite puede tener múltiples pagos o abonos (Historial).
     */
    public function payments()
    {
        return $this->hasMany(FinancePayment::class);
    }

    /**
     * Un trámite puede pertenecer a una factura si ya fue declarado.
     */
    public function invoice()
    {
        return $this->belongsTo(FinanceInvoice::class, 'finance_invoice_id');
    }
}