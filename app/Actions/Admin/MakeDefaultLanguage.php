<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($id, $exclude) {
            Language::where('default', false)
                ->when($exclude, fn ($query) => $query->whereIn('id', $exclude))
                ->when($id, fn ($query) => $query->where('id', '=', $id))
                ->update(['default' => true]);
        });
    }
}
