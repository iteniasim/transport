<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('view_users');

        $users = User::with('roles')
            ->withTrashed()
            ->paginate(10, ['id', 'name', 'email', 'deleted_at']);

        $roles = Role::all();

        return Inertia::render('users/UserIndex', compact('users', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update_users');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'exists:roles,id'],
        ]);

        $user->update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
        ]);

        if ($request->get('role')) {
            $user->syncRoles([$request->get('role')]);
        }

        return back()->with('success', 'User updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete_users');

        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(User $user): RedirectResponse
    {
        Gate::authorize('restore_users');

        $user->restore();

        return back()->with('success', 'User restored.');
    }
}
