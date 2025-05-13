<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Project;
use Exception;

final class DeleteProject
{
    /**
     * Delete a project.
     *
     * @param Project $project
     * @return void
     * @throws Exception
     */
    public function handle(Project $project): void
    {
        dbTransaction(function () use ($project) {
            $project->delete();
        });
    }
}
