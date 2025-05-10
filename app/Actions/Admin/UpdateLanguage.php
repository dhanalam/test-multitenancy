<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;
use Illuminate\Http\UploadedFile;

class UpdateLanguage
{
    /**
     * Update an existing language.
     *
     * @param Language $language
     * @param array<string, mixed> $data
     * @param UploadedFile|null $thumbnail
     * @return Language
     */
    public function handle(Language $language, array $data, ?UploadedFile $thumbnail = null): Language
    {
        $languageData = [
            'country_id' => $data['country_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'default' => $data['default'] ?? false,
        ];

        // Handle thumbnail upload
        if ($thumbnail) {
            // Delete old thumbnail if exists
            if ($language->thumbnail && file_exists(public_path($language->thumbnail))) {
                unlink(public_path($language->thumbnail));
            }

            $filename = time() . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('uploads/languages'), $filename);
            $languageData['thumbnail'] = 'uploads/languages/' . $filename;
        }

        // Handle default language
        if (($data['default'] ?? false)) {
            // Unset default for all other languages
            Language::where('id', '!=', $language->id)
                ->where('default', true)
                ->update(['default' => false]);
            $languageData['default'] = true;
        } elseif ($language->default && ! ($data['default'] ?? false)) {
            // Don't allow unsetting default if this is the only language or the only default
            if (Language::count() === 1 || Language::where('default', true)->count() === 1) {
                $languageData['default'] = true;
            }
        }

        $language->update($languageData);

        return $language;
    }
}
