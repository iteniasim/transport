<?php

namespace App\Http\Controllers;

use App\Mail\TaskRequestNotification;
use App\Models\Task;
use App\Models\TaskRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        Gate::authorize('view_tasks');

        $tasks = Task::query()
            ->when($request->has('withtrashed'), fn($query) => $query->withTrashed())
            ->when($request->has('onlytrashed'), fn($query) => $query->onlyTrashed())
            ->with(['user', 'creator', 'updater'])
            ->with(['user:id,name', 'creator:id,name', 'updater:id,name'])
            ->withCount(['requestedUsers'])
            ->paginate(10);

        $users = User::select(['id', 'name'])->get();

        return Inertia::render('Task/TaskIndex', compact('tasks', 'users'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create_tasks');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'load_type' => ['required', 'string'],
            'from' => ['required', 'string'],
            'to' => ['required', 'string'],
            'weight' => ['required', 'numeric'],
            'cost' => ['required', 'numeric', 'min:1'],
        ]);

        Task::create($data + [
                'status' => Task::STATUS_PENDING,
                'created_by' => Auth::id()
            ]);

        return back()->with('success', 'Task created successfully.');
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('update_tasks');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'load_type' => ['required', 'string'],
            'from' => ['required', 'string'],
            'to' => ['required', 'string'],
            'weight' => ['required', 'numeric'],
            'cost' => ['required', 'numeric', 'min:1'],
            'status' => ['required', 'integer', 'in:' . implode(',', [
                    Task::STATUS_PENDING,
                    Task::STATUS_IN_PROGRESS,
                    Task::STATUS_COMPLETED,
                ])],
        ]);

        $task->update($data + ['updated_by' => Auth::id()]);

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
     * Request a task from the task creator.
     */
    public function requestTask(Task $task)
    {
        Gate::authorize('request_tasks');

        if ($task->isAvailable()) {
            if (!$task->requestedUsers()->where('user_id', auth()->id())->exists()) {
                $task->requestedUsers()->attach(auth()->id(), ['status' => TaskRequest::STATUS_PENDING]);

                Mail::to($task->creator->email)->send(new TaskRequestNotification($task));

                return back()->with('success', 'Task requested successfully.');
            }

            return back()->with('info', 'You have already requested this task.');
        }

        return back()->with('error', 'You cannot request this task.');
    }

    /**
     * List of users who requested the task.
     */
    public function requestedUsers(Task $task)
    {
        return response()
            ->json([
                'requestedUsers' => $task->requestedUsers()->get()
            ]);
    }

    /**
     * Assign a task to an available user.
     */
    public function assignUser(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('assign_tasks');

        $request->validate([
            'user_id' => ['required', 'numeric', 'exists:users,id'],
        ]);

        if ($task->isAvailable()) {
            $task->updateQuietly([
                'user_id' => $request->get('user_id'),
                'status' => Task::STATUS_IN_PROGRESS,
            ]);

            return back()->with('success', 'Task assigned successfully.');
        }

        return back()->with('error', 'Task already assigned.');
    }
}
