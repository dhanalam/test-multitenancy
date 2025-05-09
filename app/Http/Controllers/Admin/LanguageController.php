<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageController extends Controller
{
    /**
     * Display a listing of the languages.
     */
    public function index(): View
    {
        $languages = Language::all();
        return view('admin.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new language.
     */
    public function create(): View
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created language in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:250|unique:languages,name',
            'code' => 'required|string|max:2|unique:languages,code',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'default' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/languages'), $filename);
            $validated['thumbnail'] = 'uploads/languages/' . $filename;
        }

        // If this is the first language or default is checked, make it default
        if (Language::count() === 0 || $request->has('default')) {
            // If default is checked, unset default for all other languages
            if ($request->has('default')) {
                Language::where('default', true)->update(['default' => false]);
            }
            $validated['default'] = true;
        }

        Language::create($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language created successfully.');
    }

    /**
     * Display the specified language.
     */
    public function show(Language $language): View
    {
        return view('admin.languages.show', compact('language'));
    }

    /**
     * Show the form for editing the specified language.
     */
    public function edit(Language $language): View
    {
        return view('admin.languages.edit', compact('language'));
    }

    /**
     * Update the specified language in storage.
     */
    public function update(Request $request, Language $language): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:250|unique:languages,name,' . $language->id,
            'code' => 'required|string|max:2|unique:languages,code,' . $language->id,
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'default' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($language->thumbnail && file_exists(public_path($language->thumbnail))) {
                unlink(public_path($language->thumbnail));
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/languages'), $filename);
            $validated['thumbnail'] = 'uploads/languages/' . $filename;
        }

        // Handle default language
        if ($request->has('default') && $request->default) {
            // Unset default for all other languages
            Language::where('id', '!=', $language->id)
                ->where('default', true)
                ->update(['default' => false]);
            $validated['default'] = true;
        } elseif ($language->default && !$request->has('default')) {
            // Don't allow unsetting default if this is the only language or the only default
            if (Language::count() === 1 || Language::where('default', true)->count() === 1) {
                $validated['default'] = true;
            }
        }

        $language->update($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully.');
    }

    /**
     * Remove the specified language from storage.
     */
    public function destroy(Language $language): RedirectResponse
    {
        // Don't allow deleting the default language if it's the only one
        if ($language->default && Language::count() === 1) {
            return redirect()->route('admin.languages.index')
                ->with('error', 'Cannot delete the only language.');
        }

        // If deleting the default language, make another one default
        if ($language->default) {
            $newDefault = Language::where('id', '!=', $language->id)->first();
            if ($newDefault) {
                $newDefault->update(['default' => true]);
            }
        }

        // Delete thumbnail if exists
        if ($language->thumbnail && file_exists(public_path($language->thumbnail))) {
            unlink(public_path($language->thumbnail));
        }

        $language->delete();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully.');
    }
}