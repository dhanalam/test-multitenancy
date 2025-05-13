<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        return $project->country_id === tenant('id');
    }

    /**
     * Determine whether the user can view the project.
     */
    public function store(User $user): bool
    {
        // in_array(getTenantId(), getAuthTenantIds())
        return true;
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        return $project->country_id === tenant('id');
    }

    /**
     * Determine whether the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->country_id === tenant('id');
    }
}
