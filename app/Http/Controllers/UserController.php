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

        return Inertia::render('User/UserIndex', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete_users');

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(User $user): RedirectResponse
    {
        Gate::authorize('delete_users');

        $user->restore();

        return back()->with('success', 'User restored.');
    }
}
