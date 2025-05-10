<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateCountryAction;
use App\Actions\Admin\DeleteCountryAction;
use App\Actions\Admin\UpdateCountryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCountryRequest;
use App\Http\Requests\Admin\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CountryController extends Controller
{
    /**
     * Display a listing of the countries.
     */
    public function index(): View
    {
        $countries = Country::all();

        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new country.
     */
    public function create(): View
    {
        return view('admin.countries.create');
    }

    /**
     * Store a newly created country in storage.
     */
    public function store(StoreCountryRequest $request, CreateCountryAction $createCountryAction): RedirectResponse
    {
        $createCountryAction->handle($request->validated());

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country created successfully.');
    }

    /**
     * Display the specified country.
     */
    public function show(Country $country): View
    {
        return view('admin.countries.show', compact('country'));
    }

    /**
     * Show the form for editing the specified country.
     */
    public function edit(Country $country): View
    {
        return view('admin.countries.edit', compact('country'));
    }

    /**
     * Update the specified country in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country, UpdateCountryAction $updateCountryAction): RedirectResponse
    {
        $updateCountryAction->handle($country, $request->validated());

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country updated successfully.');
    }

    /**
     * Remove the specified country from storage.
     */
    public function destroy(Country $country, DeleteCountryAction $deleteCountryAction): RedirectResponse
    {
        $deleteCountryAction->handle($country);

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country deleted successfully.');
    }
}
