<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('concept');
            $table->decimal('amount', 12, 2);
            $table->boolean('is_invoiced')->default(false); // facturado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_expenses');
    }
};