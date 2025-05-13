<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Actions\Tenant\CreateProject;
use App\Actions\Tenant\DeleteProject;
use App\Actions\Tenant\UpdateProject;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreProjectRequest;
use App\Http\Requests\Tenant\UpdateProjectRequest;
use App\Models\Language;
use App\Models\Project;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(): View
    {
        $projects = Project::get();

        return view('tenant.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        $languages = Language::where('country_id', getTenantId())->get();

        return view('tenant.projects.create', compact('languages'));
    }

    /**
     * Store a newly created project in storage.
     * @throws Exception
     */
    public function store(StoreProjectRequest $request, CreateProject $createProject): RedirectResponse
    {
        $createProject->handle(tenant('id'), $request->validated());

        return redirect()->route('tenant.projects.index', ['tenant' => tenant('id')])
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Request $request, Project $project): View
    {
        $request->user()->can('view', $project);
        $project->load('translations.language', 'tasks');

        return view('tenant.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Request $request, Project $project): View
    {
        $request->user()->can('update', $project);
        $project->load('translations');
        $languages = getLanguagesByTenant();

        return view('tenant.projects.edit', compact('project', 'languages'));
    }

    /**
     * Update the specified project in storage.
     * @throws Exception
     */
    public function update(UpdateProjectRequest $request, Project $project, UpdateProject $updateProject): RedirectResponse
    {
        $updateProject->handle($project, $request->validated());

        return redirect()->route('tenant.projects.index', ['tenant' => tenant('id')])
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     * @throws Exception
     */
    public function destroy(Request $request, Project $project, DeleteProject $deleteProject): RedirectResponse
    {
        $request->user()->authorize('delete', $project);

        $deleteProject->handle($project);

        return redirect()->route('tenant.projects.index', ['tenant' => tenant('id')])
            ->with('success', 'Project deleted successfully.');
    }
}