<?php

// Bootstrap the Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use the DB facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// First, check if the table exists
try {
    $tableExists = Schema::hasTable('meal_types_translation');
    echo "Table 'meal_types_translation' exists: " . ($tableExists ? "Yes" : "No") . "\n";
    
    if ($tableExists) {
        // Get table columns
        echo "Checking table structure...\n";
        $columns = DB::select("SHOW COLUMNS FROM meal_types_translation");
        
        echo "Current columns in meal_types_translation table:\n";
        $hasMealTypeId = false;
        
        foreach ($columns as $column) {
            echo "- " . $column->Field . " (" . $column->Type . ")\n";
            if ($column->Field === 'meal_type_id') {
                $hasMealTypeId = true;
            }
        }
        
        if (!$hasMealTypeId) {
            echo "\nAdding 'meal_type_id' column...\n";
            try {
                // Add meal_type_id column
                DB::statement("ALTER TABLE meal_types_translation ADD COLUMN meal_type_id BIGINT UNSIGNED NOT NULL AFTER meal_types_translation_id");
                echo "Column 'meal_type_id' added successfully!\n";
                
                // Add foreign key constraint
                DB::statement("ALTER TABLE meal_types_translation ADD CONSTRAINT fk_meal_type_id FOREIGN KEY (meal_type_id) REFERENCES meal_types(meal_types_id) ON DELETE CASCADE");
                echo "Foreign key constraint added successfully!\n";
                
                echo "\nTable structure updated successfully!\n";
            } catch (\Exception $e) {
                echo "Failed to add column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "\nColumn 'meal_type_id' already exists in the table.\n";
        }
    } else {
        echo "The meal_types_translation table doesn't exist. Please run migrations first.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 