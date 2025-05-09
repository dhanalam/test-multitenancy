<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Country Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $country->name }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Country Code') }}: {{ $country->code }}</p>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('admin.countries.edit', $country) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this country?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                {{ __('Delete') }}
                            </button>
                        </form>
                        <a href="{{ route('admin.countries.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>