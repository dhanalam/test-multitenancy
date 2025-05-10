<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;
use Exception;
use Illuminate\Http\UploadedFile;

final readonly class UpdateLanguage
{
    public function __construct(private RemoveDefaultLanguage $removeDefaultLanguage) {}

    /**
     * Update an existing language.
     *
     * @param Language $language
     * @param array<string, mixed> $data
     * @param UploadedFile|null $thumbnail
     * @return Language
     * @throws Exception
     */
    public function handle(Language $language, array $data, ?UploadedFile $thumbnail = null): Language
    {
        $default = $data['default'] ?? false;

        $languageData = [
            'country_id' => $data['country_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'default' => $default,
        ];

        // Handle thumbnail upload (outside transaction)
        if ($thumbnail) {
            $languageData['thumbnail'] = $this->updateThumbnail($language, $thumbnail);
        }

        dbTransaction(function () use ($default, $language, $languageData) {

            // Handle default language
            if ($default) {
                $this->removeDefaultLanguage->handle($language->country_id);
            }

            $language->update($languageData);
        });

        return $language;
    }

    private function updateThumbnail(Language $language, UploadedFile $thumbnail): string
    {
        if ($language->thumbnail && file_exists(public_path($language->thumbnail))) {
            unlink(public_path($language->thumbnail));
        }

        $filename = time() . '_' . $thumbnail->getClientOriginalName();
        $thumbnail->move(public_path('uploads/languages'), $filename);

        return 'uploads/languages/' . $filename;
    }
}
