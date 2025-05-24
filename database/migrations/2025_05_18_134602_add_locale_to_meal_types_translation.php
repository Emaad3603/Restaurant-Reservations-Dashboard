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
        Schema::table('meal_types_translation', function (Blueprint $table) {
            if (!Schema::hasColumn('meal_types_translation', 'locale')) {
                $table->string('locale', 5)->default('en')->after('description');
                $table->unique(['meal_type_id', 'locale']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_types_translation', function (Blueprint $table) {
            if (Schema::hasColumn('meal_types_translation', 'locale')) {
                $table->dropUnique(['meal_type_id', 'locale']);
                $table->dropColumn('locale');
            }
        });
    }
};
