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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('town');
            $table->string('ejido');
            $table->string('parcel_number');
            $table->string('work_type');
            $table->string('customer_name');
            $table->date('start_date');
            $table->unsignedFloat('area');
            $table->unsignedFloat('price');
            $table->text('description')->nullable();
            $table->json('payments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
