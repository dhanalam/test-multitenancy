<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Language;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

final readonly class CreateLanguage
{
    public function __construct(private RemoveDefaultLanguage $removeDefaultLanguage) {}

    /**
     * Create a new language.
     *
     * @param array<string, mixed> $data
     * @param UploadedFile|null $thumbnail
     * @return Language
     */
    public function handle(array $data, ?UploadedFile $thumbnail = null): Language
    {
        $default = $data['default'] ?? false;

        $languageData = [
            'country_id' => $data['country_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'default' => $default,
        ];

        // Handle thumbnail upload
        if ($thumbnail) {
            $languageData['thumbnail'] = $this->storeThumbnail($thumbnail);
        }

        return DB::transaction(function () use ($default, $languageData, $this) {
            // if has default, remove previous default language
            if ($default) {
                $this->removeDefaultLanguage->handle();
            }

            return Language::create($languageData);
        });
    }

    private function storeThumbnail(UploadedFile $thumbnail): string
    {
        $filename = time() . '_' . $thumbnail->getClientOriginalName();
        $thumbnail->move(public_path('uploads/languages'), $filename);

        return 'uploads/languages/' . $filename;
    }
}
