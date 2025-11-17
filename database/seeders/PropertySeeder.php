<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $suburbs = [
            'Borrowdale','Avondale','Mabelreign','Mt Pleasant','Highlands',
            'Greystone Park','Eastlea','Warren Park','Marlborough','Westgate'
        ];

        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        if (empty($categoryIds)) {
            return;
        }

        $customerIds = DB::table('customers')->pluck('id')->toArray();
        if (empty($customerIds)) {
            return;
        }

        // Ensure directories and placeholder present
        $placeholderSrc = public_path('assets/images/placeholder.svg');
        $titleDir = public_path('images' . config('global.PROPERTY_TITLE_IMG_PATH'));
        File::ensureDirectoryExists($titleDir);
        if (File::exists($placeholderSrc)) {
            File::copy($placeholderSrc, $titleDir . DIRECTORY_SEPARATOR . 'placeholder.svg');
        }

        $properties = [];
        for ($i = 0; $i < 10; $i++) {
            $loc = $suburbs[array_rand($suburbs)];
            $title = 'Harare ' . $loc . ' House ' . ($i+1);
            $properties[] = [
                'category_id'    => $categoryIds[array_rand($categoryIds)],
                'title'          => $title,
                'description'    => 'Nice property in ' . $loc . ', Harare.',
                'address'        => $loc . ', Harare',
                'client_address' => $loc . ', Harare',
                'propery_type'   => rand(0,1),
                'price'          => (string) rand(50000, 250000),
                'post_type'      => '1',
                'city'           => 'Harare',
                'country'        => 'Zimbabwe',
                'state'          => 'Harare',
                'title_image'    => 'placeholder.svg',
                'three_d_image'  => '',
                'video_link'     => '',
                'latitude'       => -17.8292 + mt_rand(-100,100)/10000,
                'longitude'      => 31.0522 + mt_rand(-100,100)/10000,
                'added_by'       => $customerIds[array_rand($customerIds)],
                'status'         => 1,
                'total_click'    => 0,
                'meta_title'       => $title,
                'meta_description' => 'Property in Harare: ' . $loc,
                'meta_keywords'    => 'Harare,' . $loc . ',House,Property',
                'meta_image'       => null,
                'is_premium'       => rand(0,1),
                'rentduration'     => null,
                'slug_id'          => Str::uuid(),
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        foreach ($properties as $prop) {
            $id = DB::table('propertys')->insertGetId($prop);

            // Gallery folder per property
            $galleryDir = public_path('images' . config('global.PROPERTY_GALLERY_IMG_PATH') . $id);
            File::ensureDirectoryExists($galleryDir);
            if (File::exists($placeholderSrc)) {
                File::copy($placeholderSrc, $galleryDir . DIRECTORY_SEPARATOR . 'placeholder.svg');
            }

            // Insert a couple of gallery images (same placeholder name is fine for seed)
            DB::table('property_images')->insert([
                ['propertys_id' => $id, 'image' => 'placeholder.svg', 'created_at' => $now, 'updated_at' => $now],
                ['propertys_id' => $id, 'image' => 'placeholder.svg', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }
}
