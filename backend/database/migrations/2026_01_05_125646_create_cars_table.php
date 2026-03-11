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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->integer('price');
            $table->integer('mileage');
            $table->integer('engine_size')->nullable();
            $table->integer('horsepower')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('body_type')->nullable();
            $table->string('color')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('doors')->nullable();
            $table->integer('weight')->nullable();
            $table->date('registration_valid_until')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
