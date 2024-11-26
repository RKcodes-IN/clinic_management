<?php

namespace App\Http\Controllers;

use App\DataTables\RolesDataTable;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('roles.index');
    }
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


    public function edit($id)
{
    $role = Role::findOrFail($id);
    $permissions = Permission::with('roles') // Ensure roles are eager loaded
        ->get()
        ->groupBy('model_name');

    $rolePermissions = $role->permissions->pluck('name')->toArray();

    return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|unique:roles,name,' . $id,
        'permissions' => 'required|array',
    ]);

    $role = Role::findOrFail($id);
    $role->update(['name' => $request->name]);
    $role->syncPermissions($request->permissions);

    return redirect()->route('roles.index')->with('success', 'Role updated successfully');
}

}
