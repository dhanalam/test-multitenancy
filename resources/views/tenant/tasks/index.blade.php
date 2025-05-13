<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between mb-4">
                        <div>
                            @if (request()->has('project_id'))
                                <a href="{{ route('tenant.projects.show', ['tenant' => tenant('id'), 'project' => request()->project_id]) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                    {{ __('Back to Project') }}
                                </a>
                            @endif
                        </div>
                        <a href="{{ route('tenant.tasks.create', ['tenant' => tenant('id'), request()->has('project_id') ? 'project_id=' . request()->project_id : '']) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
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
                                        {{ __('Project') }}
                                    </th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Active') }}
                                    </th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Order') }}
                                    </th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tasks as $task)
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            @if ($task->translations->isNotEmpty())
                                                {{ $task->translations->first()->name }}
                                            @else
                                                <span class="text-gray-400">{{ __('No translation') }}</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            @if ($task->project && $task->project->translations->isNotEmpty())
                                                <a href="{{ route('tenant.projects.show', ['tenant' => tenant('id'), 'project' => $task->project]) }}" class="text-blue-500 hover:text-blue-700">
                                                    {{ $task->project->translations->first()->name }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">{{ __('Unknown project') }}</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            @if ($task->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ __('Yes') }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ __('No') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            {{ $task->order_no }}
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
                                        <td colspan="5" class="py-2 px-4 border-b border-gray-200 text-center">
                                            {{ __('No tasks found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>