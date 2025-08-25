<?php

namespace Database\Seeders;

use App\Enums\RoomStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilityId = (string) Str::ulid();

        DB::table('facilities')->insert([
            'id' => $facilityId,
            'name' => 'Main Facility',
            'code' => 'FAC-001',
            'description' => 'Primary facility seeded for development.',
            'capacity' => 100,
            'status' => RoomStatus::ACTIVE,
        ]);

        DB::table('rooms')->insert([
            [
                'id' => (string) Str::ulid(),
                'room_number' => 'R-101',
                'facility_id' => $facilityId,
                'code' => 'RM-101',
                'description' => 'First room in the main facility',
                'capacity' => 10,
                'status' => RoomStatus::ACTIVE,
            ],
        ]);
    }
}
