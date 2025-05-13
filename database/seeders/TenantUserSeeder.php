<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $country1 = Country::firstWhere('id', 'de');

        $user1 = User::factory()->create([
            'name' => 'Dhan Kumar Lama',
            'email' => 'dhana@listandsell.de',
            'country_id' => $country1->id,
            'role' => UserRole::User->value,
        ]);

        $user1->countries()->attach($country1->id);

        foreach (config('tenancy.central_domains') as $domain) {
            $country1->domains()->create(['domain' => 'test.' . $domain]);
        }

        $country2 = Country::firstWhere('id', 'np');

        $user2 = User::factory()->create([
            'name' => 'Rasmi Gurung',
            'email' => 'rasmi@listandsell.de',
            'country_id' => $country2->id,
            'role' => UserRole::User->value,
        ]);

        $user2->countries()->attach($country2->id);

        foreach (config('tenancy.central_domains') as $domain) {
            $country2->domains()->create(['domain' => 'new.' . $domain]);
        }
    }
}
