<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Country;

class CreateCountryAction
{
    /**
     * Create a new country.
     *
     * @param array<string, mixed> $data
     * @return Country
     */
    public function handle(array $data): Country
    {
        return Country::create([
            'id' => $data['code'],
            'name' => $data['name'],
        ]);
    }
}
