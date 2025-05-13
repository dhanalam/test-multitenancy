<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Project;
use App\Models\ProjectTranslation;
use Exception;
use Illuminate\Support\Str;

final class CreateProject
{
    /**
     * Create a new project.
     *
     * @param string $tenantId
     * @param array<string, mixed> $data
     * @throws Exception
     */
    public function handle(string $tenantId, array $data): void
    {
        $projectData = [
            'country_id' => $tenantId,
            'is_active' => $data['is_active'] ?? true,
            'order_no' => $data['order_no'] ?? 0,
        ];

        dbTransaction(function () use ($projectData, $data) {
            // Create project
            $project = Project::create($projectData);

            // Create translations
            foreach ($data['translations'] as $translation) {
                ProjectTranslation::create([
                    'project_id' => $project->id,
                    'lang_id' => $translation['lang_id'],
                    'name' => $translation['name'],
                    'slug' => Str::slug($translation['name']),
                    'description' => $translation['description'] ?? null,
                ]);
            }
        });
    }
}