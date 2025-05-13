<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            @if ($task->translations->isNotEmpty())
                                {{ $task->translations->first()->name }}
                            @else
                                {{ __('Task') }} #{{ $task->id }}
                            @endif
                        </h3>
                        
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Project') }}: 
                            @if ($task->project && $task->project->translations->isNotEmpty())
                                <a href="{{ route('tenant.projects.show', ['tenant' => tenant('id'), 'project' => $task->project]) }}" class="text-blue-500 hover:text-blue-700">
                                    {{ $task->project->translations->first()->name }}
                                </a>
                            @else
                                <span class="text-gray-400">{{ __('Unknown project') }}</span>
                            @endif
                        </p>
                        
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Status') }}: 
                            @if ($task->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ __('Active') }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ __('Inactive') }}
                                </span>
                            @endif
                        </p>
                        
                        <p class="mt-1 text-sm text-gray-600">{{ __('Order Number') }}: {{ $task->order_no }}</p>
                        
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-800 mb-2">{{ __('Translations') }}</h4>
                            
                            <div class="space-y-4">
                                @forelse ($task->translations as $translation)
                                    <div class="p-4 border rounded-md">
                                        <h5 class="font-medium text-gray-700">{{ $translation->language->name }} ({{ $translation->language->code }})</h5>
                                        <p class="mt-1 text-sm text-gray-600">{{ __('Name') }}: {{ $translation->name }}</p>
                                        
                                        @if ($translation->description)
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">{{ __('Description') }}:</p>
                                                <p class="mt-1 text-sm text-gray-800">{{ $translation->description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">{{ __('No translations available.') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('tenant.tasks.edit', ['tenant' => tenant('id'), 'task' => $task]) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('tenant.tasks.destroy', ['tenant' => tenant('id'), 'task' => $task]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                {{ __('Delete') }}
                            </button>
                        </form>
                        <a href="{{ route('tenant.tasks.index', ['tenant' => tenant('id'), 'project_id' => $task->project_id]) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>