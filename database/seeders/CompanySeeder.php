<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            'company_name' => 'DineEase Restaurant',
            'currency_id' => 1, // Assuming currency_id 1 exists
            'company_uuid' => Str::uuid()->toString(),
            'logo_url' => null,
        ]);

        $this->command->info('Company created successfully.');
    }
}
