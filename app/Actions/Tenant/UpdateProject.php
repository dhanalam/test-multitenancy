<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Project;
use App\Models\ProjectTranslation;
use Exception;

final class UpdateProject
{
    /**
     * Update an existing project.
     *
     * @param Project $project
     * @param array<string, mixed> $data
     * @return void
     * @throws Exception
     */
    public function handle(Project $project, array $data): void
    {
        dbTransaction(function () use ($project, $data) {

            foreach ($data['translations'] as $translationData) {
                ProjectTranslation::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'lang_id' => $translationData['lang_id'],
                    ],
                    [
                        'name' => $translationData['name'],
                    ]
                );
            }
        });
    }
}
