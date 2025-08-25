<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organizations')->insert([
            'id' => (string) Str::ulid(),
            'name' => 'Club of the Year',
            'logo' => 'https://via.placeholder.com/150',
            'club_type' => 'club',
            'acronym' => 'COTY',
        ]);
    }
}
