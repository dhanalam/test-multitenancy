<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Actions\Tenant\CreateTask;
use App\Actions\Tenant\DeleteTask;
use App\Actions\Tenant\UpdateTask;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreTaskRequest;
use App\Http\Requests\Tenant\UpdateTaskRequest;
use App\Models\Language;
use App\Models\Project;
use App\Models\Task;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request): View
    {
        $projectId = $request->query('project_id');
        $tasks = $projectId
            ? Task::where('project_id', $projectId)->get()
            : Task::get();

        return view('tenant.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request): View
    {
        $languages = Language::where('country_id', getTenantId())->get();
        $projects = Project::get();
        $projectId = $request->query('project_id');

        return view('tenant.tasks.create', compact('languages', 'projects', 'projectId'));
    }

    /**
     * Store a newly created task in storage.
     * @throws Exception
     */
    public function store(StoreTaskRequest $request, CreateTask $createTask): RedirectResponse
    {
        $createTask->handle($request->validated());

        return redirect()->route('tenant.tasks.index', ['tenant' => tenant('id')])
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task.
     */
    public function show(Request $request, Task $task): View
    {
        $request->user()->can('view', $task);
        $task->load('translations.language', 'project');

        return view('tenant.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Request $request, Task $task): View
    {
        $request->user()->can('update', $task);
        $task->load('translations');
        $languages = getLanguagesByTenant();
        $projects = Project::get();

        return view('tenant.tasks.edit', compact('task', 'languages', 'projects'));
    }

    /**
     * Update the specified task in storage.
     * @throws Exception
     */
    public function update(UpdateTaskRequest $request, Task $task, UpdateTask $updateTask): RedirectResponse
    {
        $updateTask->handle($task, $request->validated());

        return redirect()->route('tenant.tasks.index', ['tenant' => tenant('id')])
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     * @throws Exception
     */
    public function destroy(Request $request, Task $task, DeleteTask $deleteTask): RedirectResponse
    {
        $request->user()->authorize('delete', $task);

        $deleteTask->handle($task);

        return redirect()->route('tenant.tasks.index', ['tenant' => tenant('id')])
            ->with('success', 'Task deleted successfully.');
    }
}
