<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Ensure advertisement image directory and placeholder
        $placeholderSrc = public_path('assets/images/placeholder.svg');
        $adDir = public_path('images' . config('global.ADVERTISEMENT_IMAGE_PATH'));
        File::ensureDirectoryExists($adDir);
        if (File::exists($placeholderSrc)) {
            File::copy($placeholderSrc, $adDir . DIRECTORY_SEPARATOR . 'placeholder.svg');
        }

        $customerId = (int) DB::table('customers')->value('id') ?: 0;
        $propertyId = (int) DB::table('propertys')->value('id') ?: 0;
        $categoryId = (int) DB::table('categories')->value('id') ?: 0;
        $packageId = (int) DB::table('packages')->value('id') ?: null;

        if (!$customerId) {
            return; // need at least one customer
        }

        $ads = [
            [
                'customer_id' => $customerId,
                'start_date' => now()->toDateString(),
                'end_date'   => null,
                'type'       => 'HomeScreen',
                'slider_id'  => null,
                'title'      => 'Featured Harare Properties',
                'category_id'=> $categoryId ?: null,
                'property_id'=> $propertyId ?: null,
                'package_id' => $packageId,
                'for'        => 'property',
                'project_id' => null,
                'is_enable'  => 1,
                'status'     => 0,
            ],
            [
                'customer_id' => $customerId,
                'start_date' => now()->toDateString(),
                'end_date'   => null,
                'type'       => 'ProductListing',
                'slider_id'  => null,
                'title'      => 'Top Listings',
                'category_id'=> $categoryId ?: null,
                'property_id'=> $propertyId ?: null,
                'package_id' => $packageId,
                'for'        => 'property',
                'project_id' => null,
                'is_enable'  => 1,
                'status'     => 0,
            ],
        ];

        foreach ($ads as $a) {
            DB::table('advertisements')->insert(array_merge($a, ['created_at' => $now, 'updated_at' => $now]));
        }
    }
}
