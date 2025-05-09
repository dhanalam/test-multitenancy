<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;
use Illuminate\Http\UploadedFile;

class CreateLanguageAction
{
    /**
     * Create a new language.
     *
     * @param array<string, mixed> $data
     * @param UploadedFile|null $thumbnail
     * @return Language
     */
    public function handle(array $data, ?UploadedFile $thumbnail = null): Language
    {
        $languageData = [
            'country_id' => $data['country_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'default' => $data['default'] ?? false,
        ];

        // Handle thumbnail upload
        if ($thumbnail) {
            $filename = time() . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('uploads/languages'), $filename);
            $languageData['thumbnail'] = 'uploads/languages/' . $filename;
        }

        // If this is the first language or default is checked, make it default
        if (Language::count() === 0 || ($data['default'] ?? false)) {
            // If default is checked, unset default for all other languages
            if ($data['default'] ?? false) {
                Language::where('default', true)->update(['default' => false]);
            }
            $languageData['default'] = true;
        }

        return Language::create($languageData);
    }
}