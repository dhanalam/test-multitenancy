<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateLanguageAction;
use App\Actions\Admin\DeleteLanguageAction;
use App\Actions\Admin\UpdateLanguageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLanguageRequest;
use App\Http\Requests\Admin\UpdateLanguageRequest;
use App\Models\Country;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LanguageController extends Controller
{
    /**
     * Display a listing of the languages.
     */
    public function index(): View
    {
        $languages = Language::with('country')->get();
        return view('admin.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new language.
     */
    public function create(): View
    {
        $countries = Country::all();
        return view('admin.languages.create', compact('countries'));
    }

    /**
     * Store a newly created language in storage.
     */
    public function store(StoreLanguageRequest $request, CreateLanguageAction $action): RedirectResponse
    {
        $action->handle($request->validated(), $request->file('thumbnail'));

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language created successfully.');
    }

    /**
     * Display the specified language.
     */
    public function show(Language $language): View
    {
        $language->load('country');
        return view('admin.languages.show', compact('language'));
    }

    /**
     * Show the form for editing the specified language.
     */
    public function edit(Language $language): View
    {
        $countries = Country::all();
        return view('admin.languages.edit', compact('language', 'countries'));
    }

    /**
     * Update the specified language in storage.
     */
    public function update(UpdateLanguageRequest $request, Language $language, UpdateLanguageAction $action): RedirectResponse
    {
        $action->handle($language, $request->validated(), $request->file('thumbnail'));

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully.');
    }

    /**
     * Remove the specified language from storage.
     */
    public function destroy(Language $language, DeleteLanguageAction $action): RedirectResponse
    {
        $result = $action->handle($language);

        if ($result !== true) {
            return redirect()->route('admin.languages.index')
                ->with('error', $result);
        }

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully.');
    }
}
