<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Task;
use App\Models\TaskTranslation;
use Exception;

final class CreateTask
{
    /**
     * Create a new task.
     *
     * @param array<string, mixed> $data
     * @throws Exception
     */
    public function handle(array $data): void
    {
        dbTransaction(function () use ($data) {
            // Create task
            $task = Task::create(['project_id' => $data['project_id']]);

            // Create translations
            foreach ($data['translations'] as $translation) {
                TaskTranslation::create([
                    'task_id' => $task->id,
                    'lang_id' => $translation['lang_id'],
                    'name' => $translation['name'],
                    'description' => $translation['description'] ?? null,
                ]);
            }
        });
    }
}
