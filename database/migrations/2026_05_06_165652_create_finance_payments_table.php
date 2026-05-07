<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_entry_id')->constrained('finance_entries')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('amount', 12, 2);
            $table->string('receipt_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_payments');
    }
};