<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Service;
use App\Models\ServiceTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'order_no' => 'integer',
            'translations' => 'required|array',
            'translations.*.lang_id' => 'required|exists:languages,id',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/services'), $filename);
                $validated['image'] = 'uploads/services/' . $filename;
            }

            // Create service
            $service = Service::create([
                'tenant_id' => tenant('id'),
                'type' => $validated['type'],
                'image' => $validated['image'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'order_no' => $validated['order_no'] ?? 0,
            ]);

            // Create translations
            foreach ($validated['translations'] as $translation) {
                ServiceTranslation::create([
                    'service_id' => $service->id,
                    'lang_id' => $translation['lang_id'],
                    'name' => $translation['name'],
                    'slug' => Str::slug($translation['name']),
                    'description' => $translation['description'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('tenant.services.index', ['tenant' => tenant('id')])
                ->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->checkTenantOwnership($service);

        $validated = $request->validate([
            'type' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'order_no' => 'integer',
            'translations' => 'required|array',
            'translations.*.lang_id' => 'required|exists:languages,id',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($service->image && file_exists(public_path($service->image))) {
                    unlink(public_path($service->image));
                }

                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/services'), $filename);
                $validated['image'] = 'uploads/services/' . $filename;
            }

            // Update service
            $service->update([
                'type' => $validated['type'],
                'image' => $validated['image'] ?? $service->image,
                'is_active' => $validated['is_active'] ?? $service->is_active,
                'order_no' => $validated['order_no'] ?? $service->order_no,
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                $translation = ServiceTranslation::updateOrCreate(
                    [
                        'service_id' => $service->id,
                        'lang_id' => $translationData['lang_id'],
                    ],
                    [
                        'name' => $translationData['name'],
                        'slug' => Str::slug($translationData['name']),
                        'description' => $translationData['description'] ?? null,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('tenant.services.index', ['tenant' => tenant('id')])
                ->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating service: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $this->checkTenantOwnership($service);

        try {
            // Delete image if exists
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }

            $service->delete();

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