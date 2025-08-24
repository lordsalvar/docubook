<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\FacilitySeeder;
use Database\Seeders\RoomSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OrganizationSeeder::class,
            FacilitySeeder::class,
        ]);
    }
}
