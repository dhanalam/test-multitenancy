<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        return $task->project->country_id === tenant('id');
    }

    /**
     * Determine whether the user can view the task.
     */
    public function store(User $user): bool
    {
        // in_array(getTenantId(), getAuthTenantIds())
        return true;
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        return $task->project->country_id === tenant('id');
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->project->country_id === tenant('id');
    }
}