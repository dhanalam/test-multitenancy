<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Actions\Tenant\CreateService;
use App\Actions\Tenant\DeleteService;
use App\Actions\Tenant\UpdateService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreServiceRequest;
use App\Http\Requests\Tenant\UpdateServiceRequest;
use App\Models\Language;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $languages = Language::where('country_id', getTenantId())->get();

        return view('tenant.services.create', compact('languages'));
    }

    /**
     * Store a newly created service in storage.
     * @throws \Exception
     */
    public function store(StoreServiceRequest $request, CreateService $createService): RedirectResponse
    {
        $createService->handle(tenant('id'), $request->validated(), $request->file('image'));

        return redirect()->route('tenant.services.index', ['tenant' => tenant('id')])->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified service.
     */
    public function show(Request $request, Service $service): View
    {
        $request->user()->authorize('view', $service);
        $service->load('translations.language');

        return view('tenant.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Request $request, Service $service): View
    {
        $request->user()->can('update', $service);
        $service->load('translations');
        $languages = getLanguagesByTenant();

        return view('tenant.services.edit', compact('service', 'languages'));
    }

    /**
     * Update the specified service in storage.
     * @throws \Exception
     */
    public function update(UpdateServiceRequest $request, Service $service, UpdateService $updateService): RedirectResponse
    {
        $updateService->handle($service, $request->validated(), $request->file('image'));

        return redirect()->route('tenant.services.index', ['tenant' => tenant('id')])
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     * @throws \Exception
     */
    public function destroy(Request $request, Service $service, DeleteService $deleteService): RedirectResponse
    {
        $request->user()->authorize('delete', $service);

        $deleteService->handle($service);

        return redirect()->route('tenant.services.index', ['tenant' => tenant('id')])
            ->with('success', 'Service deleted successfully.');
    }

}
