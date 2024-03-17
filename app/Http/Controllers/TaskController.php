<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
// Use the Task Model
use App\Models\Task;
// We will use Form Request to validate incoming requests from our store and update method
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return response()->view('tasks.index', [
            'unCompletedTasks' => Task::where('is_completed', 0)->orderBy('updated_at', 'desc')->get(),
            'completedTasks' => Task::where('is_completed', 1)->orderBy('updated_at', 'desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response()->view('tasks.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('info_file')) {
             // put file in the public storage
            $filePath = Storage::disk('public')->put('files/tasks/info-files', request()->file('info_file'));
            $validated['info_file'] = $filePath;
        }

        // insert only requests that already validated in the StoreRequest
        $create = Task::create($validated);

        if($create) {
            // add flash for the success notification
            session()->flash('notif.success', 'Task created successfully!');
            return redirect()->route('tasks.index');
        }

        return abort(500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        return response()->view('tasks.show', [
            'task' => Task::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        return response()->view('tasks.form', [
            'task' => Task::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id): RedirectResponse
    {
        $task = Task::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('info_file')) {
            // if there is an info file
            if (isset($task->info_file)) {
                // delete the file
                Storage::disk('public')->delete($task->info_file);
            }

            $filePath = Storage::disk('public')->put('files/tasks/info-files', request()->file('info_file'), 'public');
            $validated['info_file'] = $filePath;
        }

        $update = $task->update($validated);

        if($update) {
            session()->flash('notif.success', 'Task updated successfully!');
            return redirect()->route('tasks.index');
        }

        return abort(500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $task = Task::findOrFail($id);

        // if there is an info file
        if (isset($task->info_file)) {
            // delete the file
            Storage::disk('public')->delete($task->info_file);
        }
        
        $delete = $task->delete($id);

        if($delete) {
            session()->flash('notif.success', 'Task deleted successfully!');
            return redirect()->route('tasks.index');
        }

        return abort(500);
    }

    public function markCompleted(string $id): RedirectResponse
    {
        $task = Task::findOrFail($id);

        $isCompleted = $task->update(['is_completed' => 1]);

        if($isCompleted) {
            session()->flash('notif.success', 'Task marked as completed!');
            return redirect()->route('tasks.index');
        }

        return abort(500);
    }

    public function markUncompleted(string $id): RedirectResponse
    {
        $task = Task::findOrFail($id);

        $isCompleted = $task->update(['is_completed' => 0]);

        if($isCompleted) {
            session()->flash('notif.success', 'Task marked as uncompleted!');
            return redirect()->route('tasks.index');
        }

        return abort(500);
    }
}