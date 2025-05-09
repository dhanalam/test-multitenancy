<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Country;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        $this->call(CountrySeeder::class);

        // Seed languages
        $this->call(LanguageSeeder::class);

        DB::transaction(function () {
            $admin = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@listandsell.de',
                'role' => UserRole::Admin->value,
            ]);

            $tenant = Country::firstWhere('id', 'de');

            $user = User::factory()->create([
                'name' => 'Dhan Kumar Lama',
                'email' => 'dhana@listandsell.de',
                'tenant_id' => $tenant->id,
                'role' => UserRole::User->value,
            ]);

            $user->tenants()->attach($tenant->id);

            foreach (config('tenancy.central_domains') as $domain) {
                $tenant->domains()->create(['domain' => 'test.' . $domain]);
            }
        });
    }
}
