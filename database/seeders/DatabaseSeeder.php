<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*Tenant::truncate();
        User::truncate();
        Domain::truncate();*/

        // Seed countries
        $this->call([
            CountrySeeder::class,
            LanguageSeeder::class,
            AdminSeeder::class,
            TenantUserSeeder::class,
        ]);

    }
}
