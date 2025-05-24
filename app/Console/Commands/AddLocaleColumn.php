<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLocaleColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meal-types:add-locale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add locale column to meal_types_translation table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking if meal_types_translation table exists...');
        
        if (!Schema::hasTable('meal_types_translation')) {
            $this->error('Table meal_types_translation does not exist!');
            return 1;
        }
        
        $this->info('Checking if locale column exists...');
        
        // Get column details
        $columns = DB::select("SHOW COLUMNS FROM meal_types_translation");
        $hasLocale = false;
        
        foreach ($columns as $column) {
            if ($column->Field === 'locale') {
                $hasLocale = true;
                break;
            }
        }
        
        if ($hasLocale) {
            $this->info('The locale column already exists.');
            return 0;
        }
        
        $this->info('Adding locale column...');
        
        try {
            DB::statement("ALTER TABLE meal_types_translation ADD COLUMN locale VARCHAR(5) DEFAULT 'en' AFTER description");
            $this->info('Locale column added successfully!');
            
            // Check if the column was actually added
            $columns = DB::select("SHOW COLUMNS FROM meal_types_translation");
            $hasLocale = false;
            
            foreach ($columns as $column) {
                if ($column->Field === 'locale') {
                    $hasLocale = true;
                    break;
                }
            }
            
            if (!$hasLocale) {
                $this->error('Failed to verify that the locale column was added.');
                return 1;
            }
            
            $this->info('Adding unique index...');
            try {
                DB::statement('ALTER TABLE meal_types_translation ADD UNIQUE INDEX meal_types_translation_meal_type_id_locale_unique (meal_type_id, locale)');
                $this->info('Unique index added successfully!');
            } catch (\Exception $e) {
                $this->warn('Failed to add unique index: ' . $e->getMessage());
                // Continue anyway as the column is more important
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to add locale column: ' . $e->getMessage());
            return 1;
        }
    }
} 