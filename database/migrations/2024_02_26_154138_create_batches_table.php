<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedDouble('price')->nullable();
            $table->unsignedFloat('surface')->nullable();
            $table->string('address')->nullable();
            $table->string('lon')->nullable();
            $table->string('lat')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('subdivision_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
