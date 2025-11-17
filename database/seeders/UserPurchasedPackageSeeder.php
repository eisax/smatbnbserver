<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPurchasedPackageSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $customerId = (int) DB::table('customers')->value('id');
        $packageId = (int) DB::table('packages')->value('id');
        if (!$customerId || !$packageId) {
            return;
        }

        DB::table('user_purchased_packages')->updateOrInsert(
            [
                'modal_type' => 'App\\Models\\Customer',
                'modal_id' => $customerId,
                'package_id' => $packageId,
            ],
            [
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(1)->toDateString(),
                'used_limit_for_property' => 0,
                'used_limit_for_advertisement' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
