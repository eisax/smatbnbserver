<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\File;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $suburbs = [
            'Borrowdale','Avondale','Mabelreign','Mt Pleasant','Highlands',
            'Greystone Park','Eastlea','Warren Park','Marlborough','Westgate'
        ];

        // Ensure placeholder profile image available
        $placeholderSrc = public_path('assets/images/placeholder.svg');
        $usersDir = public_path('images' . config('global.USER_IMG_PATH'));
        File::ensureDirectoryExists($usersDir);
        if (File::exists($placeholderSrc)) {
            File::copy($placeholderSrc, $usersDir . DIRECTORY_SEPARATOR . 'placeholder.svg');
        }

        Customer::factory()
            ->count(10)
            ->state(function(array $attributes) use ($suburbs) {
                $loc = $suburbs[array_rand($suburbs)];
                return [
                    'city' => 'Harare',
                    'state' => 'Harare',
                    'country' => 'Zimbabwe',
                    'address' => $loc . ', Harare',
                    'profile' => 'placeholder.svg',
                ];
            })
            ->create();
    }
}
