<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Country;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($country, $data) {
            $country->update([
                'name' => $data['name'],
            ]);

            return $country;
        });
    }
}
