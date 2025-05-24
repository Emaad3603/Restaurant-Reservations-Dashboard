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
    $tableExists = Schema::hasTable('restaurants_translations');
    echo "Table 'restaurants_translations' exists: " . ($tableExists ? "Yes" : "No") . "\n";
    
    if ($tableExists) {
        // Get table columns
        echo "Checking table structure...\n";
        $columns = DB::select("SHOW COLUMNS FROM restaurants_translations");
        
        echo "Current columns in restaurants_translations table:\n";
        $hasLocale = false;
        
        foreach ($columns as $column) {
            echo "- " . $column->Field . " (" . $column->Type . ")\n";
            if ($column->Field === 'locale') {
                $hasLocale = true;
            }
        }
        
        if (!$hasLocale) {
            echo "\nAdding 'locale' column...\n";
            try {
                // Add locale column
                DB::statement("ALTER TABLE restaurants_translations ADD COLUMN locale VARCHAR(5) DEFAULT 'en' AFTER description");
                echo "Column 'locale' added successfully!\n";
                
                echo "\nTable structure updated successfully!\n";
            } catch (\Exception $e) {
                echo "Failed to add column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "\nColumn 'locale' already exists in the table.\n";
        }
    } else {
        echo "The restaurants_translations table doesn't exist.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 