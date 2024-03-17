<x-app-layout>
    {{-- Header section with 'Edit' or 'Create' depending on the existence of $task --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Use 'Edit' for edit mode and 'Create' for create mode --}}
            {{ isset($task) ? 'Edit' : 'Create' }} 
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Form for task creation/updation with file upload --}}
                    <form method="post" action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf {{-- CSRF protection --}}
                        {{-- Use PUT method for edit mode --}}
                        @isset($task)
                            @method('put')
                        @endisset

                        {{-- Task Content Input --}}
                        <div>
                            <x-input-label for="content" value="Task" /> {{-- Label for task content --}}
                            <x-text-input id="content" name="content" type="text" class="mt-1 block w-full" :value="$task->content ?? old('content')" required autofocus /> {{-- Input field for task content --}}
                            <x-input-error class="mt-2" :messages="$errors->get('content')" /> {{-- Display validation errors for task content --}}
                        </div>

                        {{-- Info File Input --}}
                        <div>
                            <x-input-label for="info_file" value="Info File" /> {{-- Label for info file --}}
                            <label class="block mt-2">
                                <span class="sr-only">Choose file</span> {{-- Screen reader text --}}
                                <input type="file" id="info_file" name="info_file" accept=".pdf,.docx,.doc,.xlsx,.xls" class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-violet-50 file:text-violet-700
                                    hover:file:bg-violet-100
                                " /> {{-- File input field --}}
                            </label>
                            {{-- Display existing file if it exists --}}
                            @isset($task->info_file)
                                <div class="shrink-0 my-2">
                                    <span>File Exists: </span> {{-- Display text indicating file existence --}}
                                    <a href="{{ Storage::url($task->info_file) }}">{{ explode('/', $task->info_file)[3] }}</a> {{-- Display file name with link --}}
                                </div>
                            @endisset
                            <x-input-error class="mt-2" :messages="$errors->get('info_file')" /> {{-- Display validation errors for info file --}}
                        </div>

                        {{-- Save and Cancel Buttons --}}
                        <div class="flex items-center gap-2">
                            <x-primary-button>{{ __('Save') }}</x-primary-button> {{-- Primary button for saving --}}
                            <x-secondary-button onclick="history.back()">{{ __('Cancel') }}</x-secondary-button> {{-- Secondary button for canceling --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>