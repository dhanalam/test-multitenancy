<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Country;

final class DeleteCountry
{
    /**
     * Delete a country.
     *
     * @param Country $country
     * @return void
     */
    public function handle(Country $country): void
    {
        $country->delete();
    }
}
