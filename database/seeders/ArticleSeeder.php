<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Ensure article images directory and placeholder
        $placeholderSrc = public_path('assets/images/placeholder.svg');
        $articleDir = public_path('images' . config('global.ARTICLE_IMG_PATH'));
        File::ensureDirectoryExists($articleDir);
        if (File::exists($placeholderSrc)) {
            File::copy($placeholderSrc, $articleDir . DIRECTORY_SEPARATOR . 'placeholder.svg');
        }

        $articles = [
            ['title' => 'Buying Property in Harare', 'image' => 'placeholder.svg', 'description' => 'Tips and insights for buying property in Harare.'],
            ['title' => 'Renting in Harare Suburbs', 'image' => 'placeholder.svg', 'description' => 'What you should know about renting in popular suburbs.'],
            ['title' => 'Home Renovation Ideas', 'image' => 'placeholder.svg', 'description' => 'Simple improvements to boost property value.'],
        ];

        foreach ($articles as $a) {
            DB::table('articles')->updateOrInsert(
                ['title' => $a['title']],
                array_merge($a, [
                    'slug_id' => generateUniqueSlug($a['title'], 2),
                    'created_at' => $now,
                    'updated_at' => $now
                ])
            );
        }
    }
}
