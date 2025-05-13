<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Project;

final class DeleteProject
{
    /**
     * Delete a project.
     *
     * @param Project $project
     * @return void
     */
    public function handle(Project $project): void
    {
        dbTransaction(function () use ($project) {
            $project->delete();
        });
    }
}