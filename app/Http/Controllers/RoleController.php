<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view_roles');

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return Inertia::render('roles/RoleIndex', compact(['roles', 'permissions']));
    }

    public function store(Request $request)
    {
        Gate::authorize('create_roles');

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
            ->with('message', 'Role created successfully');
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('update_roles');

        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->input('name')]);

        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('roles.index')
            ->with('message', 'Role updated successfully');
    }
}
