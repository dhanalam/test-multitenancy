<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tenant.tasks.update', ['tenant' => tenant('id'), 'task' => $task]) }}">
                        @csrf
                        @method('PUT')

                        <!-- Display success message -->
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-md">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Display validation errors -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-md">
                                <p class="font-medium">{{ __('Please fix the following errors:') }}</p>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Display session error message -->
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-md">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Project -->
                        <div class="mb-4">
                            <x-input-label for="project_id" :value="__('Project')" />
                            <select id="project_id" name="project_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Select a project') }}</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" {{ (old('project_id', $task->project_id) == $project->id) ? 'selected' : '' }}>
                                        @if ($project->translations->isNotEmpty())
                                            {{ $project->translations->first()->name }}
                                        @else
                                            {{ __('Project') }} #{{ $project->id }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                        </div>

                        <!-- Translations -->
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Translations') }}</h3>

                            @foreach ($languages as $language)
                                <div class="p-4 mb-4 border rounded-md">
                                    <h4 class="font-medium text-gray-800 mb-2">{{ $language->name }} ({{ $language->code }})</h4>

                                    <input type="hidden" name="translations[{{ $loop->index }}][lang_id]" value="{{ $language->id }}">

                                    @php
                                        $translation = $task->translations->where('lang_id', $language->id)->first();
                                    @endphp

                                    <!-- Name -->
                                    <div class="mb-3">
                                        <x-input-label for="translations_{{ $loop->index }}_name" :value="__('Name')" />
                                        <x-text-input id="translations_{{ $loop->index }}_name" class="block mt-1 w-full" type="text" name="translations[{{ $loop->index }}][name]" :value="old('translations.' . $loop->index . '.name', $translation ? $translation->name : '')" required />
                                        <x-input-error :messages="$errors->get('translations.' . $loop->index . '.name')" class="mt-2" />
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <x-input-label for="translations_{{ $loop->index }}_description" :value="__('Description')" />
                                        <textarea id="translations_{{ $loop->index }}_description" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" name="translations[{{ $loop->index }}][description]" rows="3">{{ old('translations.' . $loop->index . '.description', $translation ? $translation->description : '') }}</textarea>
                                        <x-input-error :messages="$errors->get('translations.' . $loop->index . '.description')" class="mt-2" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tenant.tasks.index', ['tenant' => tenant('id'), 'project_id' => $task->project_id]) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>