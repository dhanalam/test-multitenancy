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
        $taskData = [
            'project_id' => $data['project_id'] ?? $task->project_id,
            'is_active' => $data['is_active'] ?? $task->is_active,
            'order_no' => $data['order_no'] ?? $task->order_no,
        ];

        dbTransaction(function () use ($task, $taskData, $data) {
            // Update task
            $task->update($taskData);

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