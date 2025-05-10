<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Country;

final class UpdateCountry
{
    /**
     * Update an existing country.
     *
     * @param Country $country
     * @param array<string, mixed> $data
     * @return Country
     */
    public function handle(Country $country, array $data): Country
    {
        $country->update([
            'name' => $data['name'],
        ]);

        return $country;
    }
}
