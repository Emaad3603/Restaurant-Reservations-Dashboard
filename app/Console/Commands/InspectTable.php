<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InspectTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspect:table {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inspect the structure of a database table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        
        // Check if table exists
        if (!Schema::hasTable($table)) {
            $this->error("Table '{$table}' does not exist.");
            return 1;
        }
        
        // Get the column information
        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        
        // Output as a table
        $headers = ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'];
        $rows = [];
        
        foreach ($columns as $column) {
            $rows[] = (array)$column;
        }
        
        $this->table($headers, $rows);
        
        // Get the first row from the table if available
        $firstRow = DB::table($table)->first();
        
        if ($firstRow) {
            $this->info('Sample data (first row):');
            $this->line(json_encode($firstRow, JSON_PRETTY_PRINT));
        } else {
            $this->info('Table is empty, no sample data available.');
        }
        
        return 0;
    }
} 