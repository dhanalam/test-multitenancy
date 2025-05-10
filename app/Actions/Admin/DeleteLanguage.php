<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;
use Exception;

readonly class DeleteLanguage
{
    public function __construct(private MakeDefaultLanguage $makeDefaultLanguage) {}

    /**
     * Delete a language.
     *
     * @param Language $language
     * @return void
     * @throws Exception
     */
    public function handle(Language $language): void
    {
        // Don't allow deleting the default language if it's the only one
        if ($language->default && Language::count() === 1) {
            throw new Exception('Cannot delete the only language.');
        }

        // If deleting the default language, make another one default
        if ($language->default) {
            $this->makeDefaultLanguage->handle(null, [$language->id]);
        }

        // Delete thumbnail if exists
        if ($language->thumbnail && file_exists(public_path($language->thumbnail))) {
            unlink(public_path($language->thumbnail));
        }

        $language->delete();
    }
}
