<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;
use Exception;

final readonly class DeleteLanguage
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

        $thumbnailPath = $this->getThumbnailPath($language);

        $makeDefaultLanguage = $this->makeDefaultLanguage;

        dbTransaction(function () use ($language, $makeDefaultLanguage) {
            // If deleting the default language, make another one default
            if ($language->default) {
                $makeDefaultLanguage->handle(null, [$language->id]);
            }

            $language->delete();
        });

        // Delete thumbnail if exists
        if ($thumbnailPath) {
            $this->deleteThumbnail($thumbnailPath);
        }
    }

    private function getThumbnailPath(Language $language): ?string
    {
        return $language->thumbnail && file_exists(public_path($language->thumbnail))
            ? public_path($language->thumbnail)
            : null;
    }

    private function deleteThumbnail(string $thumbnailPath): void
    {
        unlink($thumbnailPath);
    }
}
