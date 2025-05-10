<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Country;
use Illuminate\Support\Facades\DB;

final class CreateCountry
{
    /**
     * Create a new country.
     *
     * @param array<string, mixed> $data
     * @return Country
     */
    public function handle(array $data): Country
    {
        return DB::transaction(function () use ($data) {
            return Country::create([
                'id' => $data['code'],
                'name' => $data['name'],
            ]);
        });
    }
}
