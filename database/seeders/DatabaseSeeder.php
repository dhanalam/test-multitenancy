<?php

declare(strict_types=1);

namespace Database\Seeders;

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

        DB::enableQueryLog();
        $tenant = Country::create([
            'id' => 'de',
            'name' => 'Germany',
            'data' => [],
        ]);

        $user = User::factory()->create([
            'name' => 'Dhan Kumar Lama',
            'email' => 'admin@listandsell.de',
            'tenant_id' => $tenant->id,
        ]);

        $user->tenants()->attach($tenant->id);

        foreach (config('tenancy.central_domains') as $domain) {
            $tenant->domains()->create(['domain' => 'test.' . $domain]);
        }

    }
}
