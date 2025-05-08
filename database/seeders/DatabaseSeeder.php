<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Stancl\Tenancy\Database\Models\Domain;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*User::truncate();
        Tenant::truncate();*/

        $tenant = Tenant::create([
            'data' => [
                'name' => 'Nepal',
                'code' => 'NP',
                'currency' => 'NPR',
                'timezone' => 'Asia/Kathmandu',
            ],
        ]);
        User::factory()->create([
            'name' => 'Dhan Kumar Lama',
            'email' => 'admin@listandsell.de',
            'tenant_id' => $tenant->id,
        ]);

        foreach (config('tenancy.central_domains') as $domain) {
            $tenant->domains()->create(['domain' => 'test.'.$domain]);
        }

    }
}
