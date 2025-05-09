<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Service;
use App\Models\ServiceTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateServiceAction
{
    /**
     * Update an existing service.
     *
     * @param Service $service
     * @param array<string, mixed> $data
     * @param UploadedFile|null $image
     * @return Service
     * @throws \Exception
     */
    public function handle(Service $service, array $data, ?UploadedFile $image = null): Service
    {
        try {
            DB::beginTransaction();

            $serviceData = [
                'type' => $data['type'],
                'is_active' => $data['is_active'] ?? $service->is_active,
                'order_no' => $data['order_no'] ?? $service->order_no,
            ];

            // Handle image upload
            if ($image) {
                // Delete old image if exists
                if ($service->image && file_exists(public_path($service->image))) {
                    unlink(public_path($service->image));
                }

                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/services'), $filename);
                $serviceData['image'] = 'uploads/services/' . $filename;
            }

            // Update service
            $service->update($serviceData);

            // Update translations
            foreach ($data['translations'] as $translationData) {
                ServiceTranslation::updateOrCreate(
                    [
                        'service_id' => $service->id,
                        'lang_id' => $translationData['lang_id'],
                    ],
                    [
                        'name' => $translationData['name'],
                        'slug' => Str::slug($translationData['name']),
                        'description' => $translationData['description'] ?? null,
                    ]
                );
            }

            DB::commit();

            return $service;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}