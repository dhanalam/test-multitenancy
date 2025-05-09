<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Actions\Tenant\CreateServiceAction;
use App\Actions\Tenant\DeleteServiceAction;
use App\Actions\Tenant\UpdateServiceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreServiceRequest;
use App\Http\Requests\Tenant\UpdateServiceRequest;
use App\Models\Language;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index(): View
    {
        $services = Service::where('tenant_id', tenant('id'))->get();
        return view('tenant.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(): View
    {
        $languages = Language::all();
        return view('tenant.services.create', compact('languages'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(StoreServiceRequest $request, CreateServiceAction $action): RedirectResponse
    {
        try {
            $action->handle(tenant('id'), $request->validated(), $request->file('image'));

            return redirect()->route('tenant.services.index', ['tenant' => tenant('id')])
                ->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating service: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service): View
    {
        $this->checkTenantOwnership($service);
        $service->load('translations.language');
        return view('tenant.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service): View
    {
        $this->checkTenantOwnership($service);
        $service->load('translations');
        $languages = Language::all();
        return view('tenant.services.edit', compact('service', 'languages'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service, UpdateServiceAction $action): RedirectResponse
    {
        $this->checkTenantOwnership($service);

        try {
            $action->handle($service, $request->validated(), $request->file('image'));

            return redirect()->route('tenant.services.index', ['tenant' => tenant('id')])
                ->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating service: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service, DeleteServiceAction $action): RedirectResponse
    {
        $this->checkTenantOwnership($service);

        try {
            $action->handle($service);

            return redirect()->route('tenant.services.index', ['tenant' => tenant('id')])
                ->with('success', 'Service deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting service: ' . $e->getMessage());
        }
    }

    /**
     * Check if the service belongs to the current tenant.
     */
    private function checkTenantOwnership(Service $service): void
    {
        if ($service->tenant_id !== tenant('id')) {
            abort(403, 'Unauthorized action.');
        }
    }
}
