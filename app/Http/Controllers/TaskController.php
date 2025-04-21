<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskRequested;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        $tasks = Task::query()
            ->when($request->has('withtrashed'), fn($query) => $query->withTrashed())
            ->when($request->has('onlytrashed'), fn($query) => $query->onlyTrashed())
            ->with(['user', 'creator', 'updater'])
            ->with(['user:id,name', 'creator:id,name'])
            ->withCount(['requestedUsers'])
            ->paginate(10);

        $users = User::select(['id', 'name'])->get();

        return Inertia::render('tasks/TaskIndex', compact('tasks', 'users'));
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
            if ($task->requestedUsers()->where('user_id', Auth::id())->exists()) {
                return back()->with('info', 'You have already requested this task.');
            }

            $task->requestedUsers()->attach(Auth::id(), ['status' => TaskUser::STATUS_PENDING]);

            $task->creator->notify(new TaskRequested($task));

            return back()->with('success', 'Task requested successfully.');
        }

        return back()->with('error', 'This task is not available for request.');
    }

    /**
     * List of users who requested the task.
     */
    public function requestedUsers(Task $task)
    {
        Gate::authorize('view_tasks');

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
            // Update the status for all requests in the pivot table except the user being assigned the task
            $task->requestedUsers()->where('user_id', '!=', $request->get('user_id'))
                ->update(['status' => TaskUser::STATUS_REJECTED]);
            // Update the status for the request by the user being assigned the task
            $task->requestedUsers()->where('user_id', $request->get('user_id'))
                ->update(['status' => TaskUser::STATUS_ACCEPTED]);

            // Assign user to the task
            $task->updateQuietly([
                'user_id' => $request->get('user_id'),
                'status' => Task::STATUS_IN_PROGRESS,
            ]);

            $assignedUser = User::find($request->get('user_id'));
            $assignedUser->notify(new TaskAssigned($task));

            //  todo: i want another notification for all the users whose request were rejected here

            return back()->with('success', 'Task assigned successfully.');
        }

        return back()->with('error', 'This task is not available for assignment.');
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
}
