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
        Schema::create('subdivisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->unsignedSmallInteger('batches_availables')->nullable();
            $table->unsignedDouble('lowest_price')->nullable();
            $table->unsignedFloat('lowest_surface')->nullable();
            $table->string('address');
            $table->text('maps_url')->nullable();
            $table->text('description')->nullable();
            $table->json('amenities')->nullable();
            $table->boolean('soon')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdivisions');
    }
};
