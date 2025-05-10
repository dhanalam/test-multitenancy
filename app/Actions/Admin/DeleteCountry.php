<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Country;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($country) {
            $country->delete();
        });
    }
}
