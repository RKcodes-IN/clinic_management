<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function create()
    {
        $permissions = Permission::with('roles') // Ensure roles are eager loaded
            ->get()
            ->groupBy('model_name');
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.create')->with('success', 'Role created successfully');
    }
}
