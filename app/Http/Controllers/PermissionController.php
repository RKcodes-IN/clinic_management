<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name|regex:/^(\w+ \w+)$/'
        ]);

        // Extract model name from permission name
        $parts = explode(' ', $request->name);
        $modelName = $parts[1];

        Permission::create([
            'name' => $request->name,
            'model_name' => $modelName
        ]);

        return redirect()->route('permissions.create')->with('success', 'Permission created successfully');
    }
}
