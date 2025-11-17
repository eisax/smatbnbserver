<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OutdoorFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Ensure facility image directory and placeholder
        $placeholderSrc = public_path('assets/images/placeholder.svg');
        $facilityDir = public_path('images' . config('global.FACILITY_IMAGE_PATH'));
        File::ensureDirectoryExists($facilityDir);
        if (File::exists($placeholderSrc)) {
            File::copy($placeholderSrc, $facilityDir . DIRECTORY_SEPARATOR . 'placeholder.svg');
        }

        $facilities = [
            ['name' => 'School', 'image' => 'placeholder.svg'],
            ['name' => 'Hospital', 'image' => 'placeholder.svg'],
            ['name' => 'Shopping Mall', 'image' => 'placeholder.svg'],
            ['name' => 'Fuel Station', 'image' => 'placeholder.svg'],
            ['name' => 'Public Transport', 'image' => 'placeholder.svg'],
        ];

        foreach ($facilities as $f) {
            DB::table('outdoor_facilities')->updateOrInsert(
                ['name' => $f['name']],
                array_merge($f, ['updated_at' => $now, 'created_at' => $now])
            );
        }

        // Assign to properties with random distances
        $propertyIds = DB::table('propertys')->pluck('id');
        $facilityIds = DB::table('outdoor_facilities')->pluck('id');
        foreach ($propertyIds as $pid) {
            // pick up to 3 facilities per property
            $pick = $facilityIds->shuffle()->take(rand(2,3));
            foreach ($pick as $fid) {
                DB::table('assigned_outdoor_facilities')->updateOrInsert(
                    ['property_id' => $pid, 'facility_id' => $fid],
                    ['distance' => rand(1, 10) * 100, 'updated_at' => $now, 'created_at' => $now]
                );
            }
        }
    }
}
