<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;

final class RemoveDefaultLanguage
{
    /**
     * Remove current default Language.
     *
     * @param string $countryId
     * @param Language|null $language
     * @return void
     */
    public function handle(string $countryId, ?Language $language = null): void
    {
        Language::where('default', true)
            ->where('country_id', $countryId)
            ->when($language, fn ($query) => $query->where('id', '!=', $language->id))
            ->update(['default' => false]);
    }
}
