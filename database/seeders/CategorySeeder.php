<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Use existing category images already uploaded under public/images/category
        $existing = [
            '1763244199.7167.svg',
            '1763244292.4257.svg',
            '1763244337.91.svg',
        ];

        $parameterIds = DB::table('parameters')->pluck('id')->toArray();
        $paramCsv = implode(',', $parameterIds);

        $categories = [
            ['category' => 'House', 'image' => $existing[0] ?? null, 'parameter_types' => $paramCsv, 'status' => 1, 'sequence' => 1],
            ['category' => 'Apartment', 'image' => $existing[1] ?? null, 'parameter_types' => $paramCsv, 'status' => 1, 'sequence' => 2],
            ['category' => 'Townhouse', 'image' => $existing[2] ?? null, 'parameter_types' => $paramCsv, 'status' => 1, 'sequence' => 3],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->updateOrInsert(
                ['category' => $cat['category']],
                array_merge($cat, [
                    'slug_id' => generateUniqueSlug($cat['category'], 3),
                    'updated_at' => $now,
                    'created_at' => $now
                ])
            );
        }
    }
}
