<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            // Germany languages
            [
                'country_id' => 'de',
                'name' => 'English',
                'code' => 'en',
                'default' => false,
            ],
            [
                'country_id' => 'de',
                'name' => 'German',
                'code' => 'de',
                'default' => true, // Default language for Germany
            ],
            [
                'country_id' => 'de',
                'name' => 'Russian',
                'code' => 'ru',
                'default' => false,
            ],

            // Nepal languages
            [
                'country_id' => 'np',
                'name' => 'English',
                'code' => 'en',
                'default' => false,
            ],
            [
                'country_id' => 'np',
                'name' => 'Nepali',
                'code' => 'np',
                'default' => true, // Default language for Nepal
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                [
                    'country_id' => $language['country_id'],
                    'code' => $language['code'],
                ],
                [
                    'name' => $language['name'],
                    'default' => $language['default'],
                ]
            );
        }
    }
}
