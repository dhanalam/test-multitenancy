<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tenant.projects.store', ['tenant' => tenant('id')]) }}">
                        @csrf

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

                        <!-- Is Active -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <x-input-label for="is_active" :value="__('Active')" class="ms-2" />
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <!-- Order No -->
                        <div class="mb-4">
                            <x-input-label for="order_no" :value="__('Order Number')" />
                            <x-text-input id="order_no" class="block mt-1 w-full" type="number" name="order_no" :value="old('order_no', 0)" min="0" />
                            <x-input-error :messages="$errors->get('order_no')" class="mt-2" />
                        </div>

                        <!-- Translations -->
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Translations') }}</h3>
                            
                            @foreach ($languages as $language)
                                <div class="p-4 mb-4 border rounded-md">
                                    <h4 class="font-medium text-gray-800 mb-2">{{ $language->name }} ({{ $language->code }})</h4>
                                    
                                    <input type="hidden" name="translations[{{ $loop->index }}][lang_id]" value="{{ $language->id }}">
                                    
                                    <!-- Name -->
                                    <div class="mb-3">
                                        <x-input-label for="translations_{{ $loop->index }}_name" :value="__('Name')" />
                                        <x-text-input id="translations_{{ $loop->index }}_name" class="block mt-1 w-full" type="text" name="translations[{{ $loop->index }}][name]" :value="old('translations.' . $loop->index . '.name')" required />
                                        <x-input-error :messages="$errors->get('translations.' . $loop->index . '.name')" class="mt-2" />
                                    </div>
                                    
                                    <!-- Description -->
                                    <div>
                                        <x-input-label for="translations_{{ $loop->index }}_description" :value="__('Description')" />
                                        <textarea id="translations_{{ $loop->index }}_description" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" name="translations[{{ $loop->index }}][description]" rows="3">{{ old('translations.' . $loop->index . '.description') }}</textarea>
                                        <x-input-error :messages="$errors->get('translations.' . $loop->index . '.description')" class="mt-2" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tenant.projects.index', ['tenant' => tenant('id')]) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>