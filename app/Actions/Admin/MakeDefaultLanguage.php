<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;

final class MakeDefaultLanguage
{
    /**
     * Remove current default Language.
     *
     * @param int|null $id
     * @param array|null $exclude
     * @return void
     */
    public function handle(?int $id = null, ?array $exclude = []): void
    {
        Language::where('default', false)
            ->when($exclude, fn ($query) => $query->whereIn('id', $exclude))
            ->when($id, fn ($query) => $query->where('id', '=', $id))
            ->update(['default' => true]);
    }
}
