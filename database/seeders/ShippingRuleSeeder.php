<?php

namespace Database\Seeders;

use App\Models\ShippingRule;
use Illuminate\Database\Seeder;

class ShippingRuleSeeder extends Seeder
{
    public function run(): void
    {
        $wilayas = [
            ['wilaya' => 'الجزائر', 'wilaya_code' => '16', 'home' => 500, 'desk' => 300],
            ['wilaya' => 'وهران', 'wilaya_code' => '31', 'home' => 600, 'desk' => 400],
            ['wilaya' => 'قسنطينة', 'wilaya_code' => '25', 'home' => 650, 'desk' => 450],
            ['wilaya' => 'تلمسان', 'wilaya_code' => '13', 'home' => 550, 'desk' => 350],
            ['wilaya' => 'عنابة', 'wilaya_code' => '23', 'home' => 700, 'desk' => 500],
            ['wilaya' => 'سيدي بلعباس', 'wilaya_code' => '22', 'home' => 600, 'desk' => 400],
            ['wilaya' => 'بسكرة', 'wilaya_code' => '04', 'home' => 550, 'desk' => 350],
            ['wilaya' => 'سطيف', 'wilaya_code' => '19', 'home' => 600, 'desk' => 400],
            ['wilaya' => 'تيزي وزو', 'wilaya_code' => '15', 'home' => 500, 'desk' => 300],
            ['wilaya' => 'الموصلية', 'wilaya_code' => '24', 'home' => 700, 'desk' => 500],
        ];

        foreach ($wilayas as $wilaya) {
            ShippingRule::firstOrCreate(
                ['wilaya_code' => $wilaya['wilaya_code']],
                [
                    'wilaya' => $wilaya['wilaya'],
                    'home_delivery_fee' => $wilaya['home'],
                    'desk_delivery_fee' => $wilaya['desk'],
                ]
            );
        }
    }
}
