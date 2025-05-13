<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Project;
use App\Models\ProjectTranslation;
use Exception;
use Illuminate\Support\Str;

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
        $projectData = [
            'is_active' => $data['is_active'] ?? $project->is_active,
            'order_no' => $data['order_no'] ?? $project->order_no,
        ];

        dbTransaction(function () use ($project, $projectData, $data) {
            // Update project
            $project->update($projectData);

            // Update translations
            foreach ($data['translations'] as $translationData) {
                ProjectTranslation::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'lang_id' => $translationData['lang_id'],
                    ],
                    [
                        'name' => $translationData['name'],
                        'slug' => Str::slug($translationData['name']),
                        'description' => $translationData['description'] ?? null,
                    ]
                );
            }
        });
    }
}