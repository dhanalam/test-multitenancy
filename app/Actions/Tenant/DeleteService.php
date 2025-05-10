<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Service;

final class DeleteService
{
    /**
     * Delete a service.
     *
     * @param Service $service
     * @return void
     */
    public function handle(Service $service): void
    {
        // Delete image if exists
        if ($service->image && file_exists(public_path($service->image))) {
            unlink(public_path($service->image));
        }

        dbTransaction(function () use ($service) {
            $service->delete();
        });
    }
}
