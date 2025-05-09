<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Service;
use App\Models\ServiceTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateServiceAction
{
    /**
     * Create a new service.
     *
     * @param string $tenantId
     * @param array<string, mixed> $data
     * @param UploadedFile|null $image
     * @return Service
     * @throws \Exception
     */
    public function handle(string $tenantId, array $data, ?UploadedFile $image = null): Service
    {
        try {
            DB::beginTransaction();

            $serviceData = [
                'tenant_id' => $tenantId,
                'type' => $data['type'],
                'is_active' => $data['is_active'] ?? true,
                'order_no' => $data['order_no'] ?? 0,
            ];

            // Handle image upload
            if ($image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/services'), $filename);
                $serviceData['image'] = 'uploads/services/' . $filename;
            }

            // Create service
            $service = Service::create($serviceData);

            // Create translations
            foreach ($data['translations'] as $translation) {
                ServiceTranslation::create([
                    'service_id' => $service->id,
                    'lang_id' => $translation['lang_id'],
                    'name' => $translation['name'],
                    'slug' => Str::slug($translation['name']),
                    'description' => $translation['description'] ?? null,
                ]);
            }

            DB::commit();

            return $service;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}