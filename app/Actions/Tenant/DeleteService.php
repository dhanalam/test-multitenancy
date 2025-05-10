<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Service;
use Exception;

class DeleteService
{
    /**
     * Delete a service.
     *
     * @param Service $service
     * @return bool
     * @throws Exception
     */
    public function handle(Service $service): bool
    {
        try {
            // Delete image if exists
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }

            $service->delete();

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
