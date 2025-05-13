<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Task;

final class DeleteTask
{
    /**
     * Delete a task.
     *
     * @param Task $task
     * @return void
     */
    public function handle(Task $task): void
    {
        dbTransaction(function () use ($task) {
            $task->delete();
        });
    }
}
