<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        // $role = Role::create(['name' => 'admin']);
        // $permission = Permission::create(['name' => 'edit users']);
        // $role->givePermissionTo($permission);
        // $permission->assignRole($role);
        // dd(Auth::user()->getRoleNames());
        return $dataTable->render('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Assign role
        $role = Role::where('name', $request->input('role'))->firstOrFail();

        $user->assignRole($request->input('role'));

        $user->syncPermissions($role->permissions->pluck('name'));
        // Redirect with success message
        return redirect()->route('users.create')->with('success', 'User created successfully.');
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
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required',
        ]);

        // Find the user
        $user = User::findOrFail($id);

        // Update user details
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        // Update role
        $role = Role::where('name', $request->input('role'))->firstOrFail();
        $user->syncRoles([$role->name]);
        $user->syncPermissions($role->permissions->pluck('name'));

        // Redirect with success message
        return redirect()->route('users.edit', $id)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
