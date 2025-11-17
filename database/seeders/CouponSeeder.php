<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percent',
            'value' => 10,
            'min_cart_amount' => 0,
            'max_uses' => null,
            'max_uses_per_user' => 1,
            'used_count' => 0,
            'starts_at' => Carbon::now()->subDay(),
            'ends_at' => Carbon::now()->addDays(30),
            'active' => true,
        ]);

        Coupon::create([
            'code' => 'TAKA100',
            'type' => 'fixed',
            'value' => 100,
            'min_cart_amount' => 500,
            'max_uses' => 100,
            'max_uses_per_user' => 2,
            'used_count' => 0,
            'starts_at' => Carbon::now()->subDay(),
            'ends_at' => Carbon::now()->addDays(60),
            'active' => true,
        ]);
    }
}
