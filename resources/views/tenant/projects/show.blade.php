<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Project Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            @if ($project->translations->isNotEmpty())
                                {{ $project->translations->first()->name }}
                            @else
                                {{ __('Project') }} #{{ $project->id }}
                            @endif
                        </h3>

                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-800 mb-2">{{ __('Translations') }}</h4>
                            
                            <div class="space-y-4">
                                @forelse ($project->translations as $translation)
                                    <div class="p-4 border rounded-md">
                                        <h5 class="font-medium text-gray-700">{{ $translation->language->name }} ({{ $translation->language->code }})</h5>
                                        <p class="mt-1 text-sm text-gray-600">{{ __('Name') }}: {{ $translation->name }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">{{ __('No translations available.') }}</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Tasks Section -->
                        <div class="mt-6">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-md font-medium text-gray-800">{{ __('Tasks') }}</h4>
                                <a href="{{ route('tenant.tasks.create', ['tenant' => tenant('id'), 'project_id' => $project->id]) }}" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                    {{ __('Add Task') }}
                                </a>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Name') }}
                                            </th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($project->tasks as $task)
                                            <tr>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    @if ($task->translations->isNotEmpty())
                                                        {{ $task->translations->first()->name }}
                                                    @else
                                                        <span class="text-gray-400">{{ __('No translation') }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('tenant.tasks.show', ['tenant' => tenant('id'), 'task' => $task]) }}" class="text-blue-500 hover:text-blue-700">
                                                            {{ __('View') }}
                                                        </a>
                                                        <a href="{{ route('tenant.tasks.edit', ['tenant' => tenant('id'), 'task' => $task]) }}" class="text-yellow-500 hover:text-yellow-700">
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form action="{{ route('tenant.tasks.destroy', ['tenant' => tenant('id'), 'task' => $task]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-2 px-4 border-b border-gray-200 text-center">
                                                    {{ __('No tasks found for this project.') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('tenant.projects.edit', ['tenant' => tenant('id'), 'project' => $project]) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('tenant.projects.destroy', ['tenant' => tenant('id'), 'project' => $project]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                {{ __('Delete') }}
                            </button>
                        </form>
                        <a href="{{ route('tenant.projects.index', ['tenant' => tenant('id')]) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>