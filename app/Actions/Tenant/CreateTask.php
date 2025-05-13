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
        $taskData = [
            'project_id' => $data['project_id'],
            'is_active' => $data['is_active'] ?? true,
            'order_no' => $data['order_no'] ?? 0,
        ];

        dbTransaction(function () use ($taskData, $data) {
            // Create task
            $task = Task::create($taskData);

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