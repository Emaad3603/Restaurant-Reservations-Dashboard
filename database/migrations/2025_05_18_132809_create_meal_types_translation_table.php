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
        Schema::create('meal_types_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meal_type_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('locale', 5);
            $table->timestamps();
            
            $table->foreign('meal_type_id')->references('meal_types_id')->on('meal_types')->onDelete('cascade');
            $table->unique(['meal_type_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_types_translation');
    }
};
