<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;

class DeleteLanguage
{
    /**
     * Delete a language.
     *
     * @param Language $language
     * @return bool|string Returns true if successful, or an error message if not
     * @throws \Exception
     */
    public function handle(Language $language): bool|string
    {
        // Don't allow deleting the default language if it's the only one
        if ($language->default && Language::count() === 1) {
            throw new \Exception('Cannot delete the only language.');
        }

        // If deleting the default language, make another one default
        if ($language->default) {
            $newDefault = Language::where('id', '!=', $language->id)->first();
            if ($newDefault) {
                $newDefault->update(['default' => true]);
            }
        }

        // Delete thumbnail if exists
        if ($language->thumbnail && file_exists(public_path($language->thumbnail))) {
            unlink(public_path($language->thumbnail));
        }

        $language->delete();
    }
}
