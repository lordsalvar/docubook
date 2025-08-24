<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('facilities')->insert([
        [
            'name' => 'Facility 1',
            'description' => 'Facility 1 description',
            'address' => 'Facility 1 address',
            'phone' => 'Facility 1 phone',
            'email' => 'Facility 1 email',
            'website' => 'Facility 1 website',
        ]
       ]);

       DB::table('rooms')->insert([
        [
            'name' => 'Room 1',
            'description' => 'Room 1 description',
            'facility_id' => 1,
        ]
       ]);
    }
}
