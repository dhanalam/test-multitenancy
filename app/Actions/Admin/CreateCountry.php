<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Country;
use Exception;

final class CreateCountry
{
    /**
     * Create a new country.
     *
     * @param array<string, mixed> $data
     * @throws Exception
     */
    public function handle(array $data): void
    {
        dbTransaction(function () use ($data) {
            Country::create([
                'id' => $data['code'],
                'name' => $data['name'],
            ]);
        });
    }
}
