<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;
use Illuminate\Support\Facades\DB;

final class RemoveDefaultLanguage
{
    /**
     * Remove current default Language.
     *
     * @param Language|null $language
     * @return void
     */
    public function handle(?Language $language = null): void
    {
        DB::transaction(function () use ($language) {
            Language::where('default', true)
                ->when($language, fn ($query) => $query->where('id', '!=', $language->id))
                ->update(['default' => false]);
        });
    }
}
