<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Ensure slider directory and placeholder
        $placeholderSrc = public_path('assets/images/placeholder.svg');
        $sliderDir = public_path('images' . config('global.SLIDER_IMG_PATH'));
        File::ensureDirectoryExists($sliderDir);
        if (File::exists($placeholderSrc)) {
            File::copy($placeholderSrc, $sliderDir . DIRECTORY_SEPARATOR . 'placeholder.svg');
        }

        $categoryId = (int) DB::table('categories')->value('id') ?: 0;
        $propertyId = (int) DB::table('propertys')->value('id') ?: 0;

        $sliders = [
            ['image' => 'placeholder.svg', 'sequence' => 1, 'category_id' => $categoryId, 'propertys_id' => $propertyId],
            ['image' => 'placeholder.svg', 'sequence' => 2, 'category_id' => $categoryId, 'propertys_id' => 0],
            ['image' => 'placeholder.svg', 'sequence' => 3, 'category_id' => 0, 'propertys_id' => $propertyId],
        ];

        foreach ($sliders as $s) {
            DB::table('sliders')->updateOrInsert(
                ['sequence' => $s['sequence']],
                array_merge($s, ['updated_at' => $now, 'created_at' => $now])
            );
        }
    }
}
