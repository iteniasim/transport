<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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
    public function index(Request $request)
    {
        Gate::authorize('view_tasks');

        $tasks = Task::query()
            ->when($request->has('withtrashed'), function ($query) {
                $query->withTrashed();
            })
            ->when($request->has('onlytrashed'), function ($query) {
                $query->onlyTrashed();
            })
            ->with(['user', 'creator', 'updater'])
            ->paginate(10, ['id', 'title', 'description', 'status', 'user_id', 'created_by', 'updated_by', 'deleted_at']);

        $users = User::select(['id', 'name'])->get();

        return Inertia::render('Task/TaskIndex', compact('tasks', 'users'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create_tasks');

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => Task::STATUS_PENDING,
            'user_id' => $request->input('user_id'),
            'created_by' => Auth::user()->id,
        ]);

        return back()->with('success', 'Task created successfully.');
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('update_tasks');

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'integer', 'in:' . implode(',', [
                    Task::STATUS_PENDING,
                    Task::STATUS_IN_PROGRESS,
                    Task::STATUS_COMPLETED,
                ])],
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
    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete_tasks');

        $task->delete();

        return back()->with('success', 'Task deleted successfully.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Task $task): RedirectResponse
    {
        Gate::authorize('restore_tasks');

        $task->restore();

        return back()->with('success', 'Task restored.');
    }

    /**
     * Claim a task by assigning it to the authenticated user.
     */
    public function claim(Task $task): RedirectResponse
    {
        Gate::authorize('claim_tasks');

        // Check if user_id is null or matches the authenticated user's id, and status is STATUS_PENDING
        if (($task->user_id === null || $task->user_id === Auth::user()->id) && $task->status === Task::STATUS_PENDING) {
            $task->updateQuietly([
                'user_id' => Auth::user()->id,
                'status' => Task::STATUS_IN_PROGRESS,
            ]);

            return back()->with('success', 'Task claimed successfully.');
        }

        return back()->with('error', 'You cannot claim this task.');
    }
}
