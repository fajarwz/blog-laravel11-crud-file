<x-app-layout>
    <!-- Define a slot named "header" -->
    <x-slot name="header">
        <!-- Flex container with space between elements -->
        <div class="flex justify-between">
            <!-- Title for the page -->
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ 'Tasks' }} <!-- Static title -->
            </h2>
            <!-- Link to add a new task -->
            <a href="{{ route('tasks.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">ADD</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <!-- Title for uncompleted tasks -->
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">Uncompleted</h3>
                    <!-- Table to display uncompleted tasks -->
                    <table class="border-collapse table-auto w-full text-sm">
                        <thead>
                            <tr>
                                <!-- Table header for task and action -->
                                <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 text-left">Task</th>
                                <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            {{-- Loop through uncompleted tasks --}}
                            @forelse ($unCompletedTasks as $task)
                            <tr>
                                <!-- Display task content -->
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <!-- Checkbox to mark task as completed -->
                                    <a class="mr-1 text-lg" href="{{ route('tasks.mark-completed', $task->id) }}">
                                        🔲
                                    </a>
                                    <!-- Task content -->
                                    <span>
                                        {{ $task->content }}
                                    </span>
                                    <!-- Display info file if exists -->
                                    @isset ($task->info_file)
                                    <span>
                                        <small> | <a href="{{ Storage::url($task->info_file) }}">File</a></small>
                                    </span>
                                    @endisset
                                    <!-- Display last update time -->
                                    <span>
                                        <small>{{ ' | ' . $task->updated_at->diffForHumans() }}</small>
                                    </span>
                                </td>
                                <!-- Actions for the task -->
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <!-- Link to edit task -->
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="border border-yellow-500 hover:bg-yellow-500 hover:text-white px-4 py-2 rounded-md">EDIT</a>
                                    <!-- Form to delete task -->
                                    <form method="post" action="{{ route('tasks.destroy', $task->id) }}" class="inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="border border-red-500 hover:bg-red-500 hover:text-white px-4 py-2 rounded-md h-[35px] relative top-[1px]">DELETE</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <!-- Display message if no uncompleted tasks -->
                            <tr>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400" colspan="2">
                                    No data can be shown.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <!-- Title for completed tasks -->
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">Completed</h3>
                    <!-- Table to display completed tasks -->
                    <table class="border-collapse table-auto w-full text-sm">
                        <thead>
                            <tr>
                                <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 text-left">Task</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            {{-- populate our task data --}}
                            @forelse ($completedTasks as $task)
                            <tr>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400 justify-center items-center">
                                    <a class="mr-1 text-lg" href="{{ route('tasks.mark-uncompleted', $task->id) }}">
                                        ✅
                                    </a>
                                    <span>
                                        {{ $task->content }}
                                    </span>
                                    @isset ($task->info_file)
                                    <span>
                                        <small> | <a href="{{ Storage::url($task->info_file) }}">File</a></small>
                                    </span>
                                    @endisset
                                    <span>
                                        <small>{{ ' | ' . $task->updated_at->diffForHumans() }}</small>
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400" colspan="2">
                                    No data can be shown.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>