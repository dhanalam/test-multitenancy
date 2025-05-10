<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'id' => 'de',
                'name' => 'Germany',
            ],
            [
                'id' => 'np',
                'name' => 'Nepal',
            ],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['id' => $country['id']],
                ['name' => $country['name']]
            );
        }
    }
}
