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
            ->when($request->has('withtrashed'), function ($query) {
                $query->withTrashed();
            })
            ->when($request->has('onlytrashed'), function ($query) {
                $query->onlyTrashed();
            })
            ->with(['user', 'creator', 'updater', 'requestedUsers'])
            ->paginate(10);

        // Add a flag for each task to check if the authenticated user has requested it
        $tasks->getCollection()->transform(function ($task) {
            $task->request_submitted = $task->request_submitted(); // Use the helper method
            $task->makeHidden('requestedUsers');
            return $task;
        });

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
            'name' => ['required', 'string', 'max:255'],
            'load_type' => ['required', 'string'],
            'from' => ['required', 'string'],
            'to' => ['required', 'string'],
            'weight' => ['required', 'numeric'],
            'cost' => ['required', 'numeric', 'min:1'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        Task::create([
            'name' => $request->input('name'),
            'load_type' => $request->input('load_type'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'weight' => $request->input('weight'),
            'cost' => $request->input('cost'),
            'status' => Task::STATUS_PENDING,
            'user_id' => $request->input('user_id') ?? Auth::user()->id, // Assign current user if null
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

        $task->update([
            'name' => $request->input('name'),
            'load_type' => $request->input('load_type'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'weight' => $request->input('weight'),
            'cost' => $request->input('cost'),
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

    /**
     * Request a task from the task creator.
     */
    public function request(Task $task)
    {
        Gate::authorize('request_tasks');

        if ($task->isAvailableForRequest()) {
            if (!$task->requestedUsers()->where('user_id', auth()->id())->exists()) {
                $task->requestedUsers()->attach(auth()->id(), ['status' => TaskRequest::STATUS_PENDING]);

                Mail::to($task->creator->email)->send(new TaskRequestNotification($task));

                return back()->with('success', 'Task requested successfully.');
            }

            return back()->with('info', 'You have already requested this task.');
        }

        return back()->with('error', 'You cannot request this task.');
    }
}
