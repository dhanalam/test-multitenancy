<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Language Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $language->name }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Language Code') }}: {{ $language->code }}</p>
                        
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">
                                {{ __('Default Language') }}: 
                                @if ($language->default)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ __('Yes') }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ __('No') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        @if ($language->thumbnail)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-2">{{ __('Thumbnail') }}:</p>
                                <img src="{{ asset($language->thumbnail) }}" alt="{{ $language->name }}" class="h-16">
                            </div>
                        @endif
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('admin.languages.edit', $language) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('admin.languages.destroy', $language) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this language?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                {{ __('Delete') }}
                            </button>
                        </form>
                        <a href="{{ route('admin.languages.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>