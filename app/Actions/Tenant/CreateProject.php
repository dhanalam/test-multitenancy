<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Project;
use App\Models\ProjectTranslation;
use Exception;

final class CreateProject
{
    /**
     * Create a new project.
     *
     * @param array<string, mixed> $data
     * @throws Exception
     */
    public function handle(array $data): void
    {
        dbTransaction(function () use ($data) {
            $project = Project::create([]);

            foreach ($data['translations'] as $translation) {
                ProjectTranslation::create([
                    'project_id' => $project->id,
                    'lang_id' => $translation['lang_id'],
                    'name' => $translation['name'],
                ]);
            }
        });
    }
}
