<?php

// Bootstrap the Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use the DB facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Checking if 'locale' column exists in meal_types_translation table...\n";

// First, check if the column exists
$hasColumn = DB::select("
    SELECT COLUMN_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'meal_types_translation' 
    AND COLUMN_NAME = 'locale'
");

if (empty($hasColumn)) {
    echo "Column 'locale' does not exist. Adding it now...\n";
    
    try {
        // Add the column
        DB::statement("ALTER TABLE meal_types_translation ADD COLUMN locale VARCHAR(5) DEFAULT 'en' AFTER description");
        echo "Column 'locale' added successfully!\n";
        
        // Check if there's a unique constraint
        $hasIndex = DB::select("
            SELECT INDEX_NAME 
            FROM INFORMATION_SCHEMA.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'meal_types_translation' 
            AND INDEX_NAME = 'meal_types_translation_meal_type_id_locale_unique'
        ");
        
        if (empty($hasIndex)) {
            echo "Adding unique constraint for meal_type_id and locale...\n";
            
            try {
                DB::statement("ALTER TABLE meal_types_translation ADD UNIQUE INDEX meal_types_translation_meal_type_id_locale_unique (meal_type_id, locale)");
                echo "Unique constraint added successfully!\n";
            } catch (\Exception $e) {
                echo "Failed to add unique constraint: " . $e->getMessage() . "\n";
            }
        } else {
            echo "Unique constraint already exists.\n";
        }
    } catch (\Exception $e) {
        echo "Failed to add column: " . $e->getMessage() . "\n";
    }
} else {
    echo "Column 'locale' already exists in the table.\n";
}

echo "Script completed.\n"; 