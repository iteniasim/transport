<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(): Response
    {
        Gate::authorize('view_tasks');

        $tasks = Task::with(['user', 'creator', 'updater'])
            ->paginate(10, ['id', 'title', 'description', 'status', 'user_id', 'created_by', 'updated_by']);

        $users = \App\Models\User::select('id', 'name')->get();

        return Inertia::render('Task/TaskIndex', compact('tasks', 'users'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create_tasks');

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'integer'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'user_id' => $request->input('user_id'),
            'created_by' => Auth::user()->id,
        ]);

        return back()->with('success', 'Task created successfully.');
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        Gate::authorize('update_tasks');

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'integer'],
        ]);

        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'updated_by' => Auth::user()->id,
        ]);

        return back()->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete_tasks');

        $task->delete();

        return back()->with('success', 'Task deleted successfully.');
    }

    /**
     * Claim a task by assigning it to the authenticated user.
     */
    public function claim(Task $task)
    {
        Gate::authorize('update_tasks');

        $task->updateQuietly([
            'user_id' => Auth::user()->id,
        ]);

        return back()->with('success', 'Task claimed successfully.');
    }
}
