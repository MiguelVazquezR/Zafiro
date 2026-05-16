<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('invoice_folio')->nullable();
            $table->decimal('total_fees', 12, 2)->default(0); // Honorarios totales
            $table->decimal('total_iva', 12, 2)->default(0); // IVA total
            $table->decimal('grand_total', 12, 2)->default(0); // Total General
            $table->decimal('sat_payment', 12, 2)->default(0); // Pago al SAT
            $table->decimal('released_balance', 12, 2)->default(0); // Saldo liberado
            $table->integer('liquidated_records_count')->default(0);
            
            // Llave foránea para saber quién registró la factura
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_invoices');
    }
};