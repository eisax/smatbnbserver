<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParameterSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $parameters = [
            ['name' => 'Bedrooms', 'type_of_parameter' => 'number', 'type_values' => null, 'image' => null],
            ['name' => 'Bathrooms', 'type_of_parameter' => 'number', 'type_values' => null, 'image' => null],
            ['name' => 'Area (sqm)', 'type_of_parameter' => 'number', 'type_values' => null, 'image' => null],
            ['name' => 'Furnished', 'type_of_parameter' => 'select', 'type_values' => 'Yes,No', 'image' => null],
            ['name' => 'Parking', 'type_of_parameter' => 'number', 'type_values' => null, 'image' => null],
        ];

        foreach ($parameters as $param) {
            DB::table('parameters')->updateOrInsert(
                ['name' => $param['name']],
                array_merge($param, ['updated_at' => $now, 'created_at' => $now])
            );
        }
    }
}
