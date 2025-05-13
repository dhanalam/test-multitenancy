<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Task;
use App\Models\TaskTranslation;
use Exception;

final class UpdateTask
{
    /**
     * Update an existing task.
     *
     * @param Task $task
     * @param array<string, mixed> $data
     * @return void
     * @throws Exception
     */
    public function handle(Task $task, array $data): void
    {

        dbTransaction(function () use ($task, $data) {
            // Update task
            $task->update(['project_id' => $data['project_id'] ?? $task->project_id]);

            // Update translations
            foreach ($data['translations'] as $translationData) {
                TaskTranslation::updateOrCreate(
                    [
                        'task_id' => $task->id,
                        'lang_id' => $translationData['lang_id'],
                    ],
                    [
                        'name' => $translationData['name'],
                        'description' => $translationData['description'] ?? null,
                    ]
                );
            }
        });
    }
}
