<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Country') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.countries.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- ID -->
                        <div class="mb-4">
                            <x-input-label for="code" :value="__('ID (2 characters)')" />
                            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required maxlength="2" />
                            <p class="mt-1 text-sm text-gray-500">{{ __('This will be used as the country ID and cannot be changed after creation.') }}</p>
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.countries.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 mr-2">
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
</x-admin-layout>
