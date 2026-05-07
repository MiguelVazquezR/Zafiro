<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('client_name');
            $table->string('phone')->nullable();
            $table->string('service_type'); 
            $table->string('location')->nullable();
            
            $table->decimal('original_amount', 12, 2)->default(0);
            $table->enum('iva_mode', ['con_iva', 'sin_iva'])->default('sin_iva');
            
            $table->decimal('fees', 12, 2)->default(0); // Honorarios
            $table->decimal('iva', 12, 2)->default(0);
            $table->decimal('total_with_iva', 12, 2)->default(0);
            $table->decimal('advance_payment', 12, 2)->default(0); // Anticipo
            $table->decimal('balance', 12, 2)->default(0); // Saldo
            
            $table->string('status')->default('En Proceso'); // En Proceso, Completado, Suspendido, Cancelado
            
            $table->boolean('is_declared')->default(false); // declarado
            $table->string('receipt_number')->nullable();
            
            // Relación con Factura (Si ya fue declarado)
            $table->foreignId('finance_invoice_id')->nullable()->constrained('finance_invoices')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_entries');
    }
};