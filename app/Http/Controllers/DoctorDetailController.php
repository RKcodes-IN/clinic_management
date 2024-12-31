<?php

namespace App\Http\Controllers;

use App\DataTables\DoctorDetailDataTable;
use App\Models\DoctorDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class DoctorDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DoctorDetailDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('doctor-details.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('doctor-details.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'age' => 'required|integer',
            'gender' => 'required|string|in:Male,Female,Other',
            'education' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:1000',
        ]);

        // dd($request);

        // Create User
        $role = Role::where('name', 'doctor')->firstOrFail();

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('phone')); // Password set as phone number
        $user->phone = $request->input('phone');
        $user->user_role = $role->id;
        $user->save();

        $user->assignRole($role->name);

        $user->syncPermissions($role->permissions->pluck('name'));
        // Create DoctorDetail
        $doctorDetail = new DoctorDetail();
        $doctorDetail->user_id = $user->id;
        $doctorDetail->name = $request->input('name');
        $doctorDetail->age = $request->input('age');
        $doctorDetail->gender = $request->input('gender');
        $doctorDetail->education = $request->input('education');
        $doctorDetail->specialty = $request->input('specialty');
        $doctorDetail->phone = $request->input('phone');
        $doctorDetail->address = $request->input('address');

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $doctorDetail->profile_image = $imagePath;
        }

        $doctorDetail->save();

        return redirect()->route('users.index')->with('success', 'User and Doctor Detail created successfully.');
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
        $doctorDetail = DoctorDetail::with('user')->findOrFail($id);

        return view('doctor-details.edit', compact('doctorDetail'));
    }

    public function update(Request $request, $id)
    {
        $doctorDetail = DoctorDetail::findOrFail($id);
        $user = $doctorDetail->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'age' => 'required|integer',
            'gender' => 'required|string|in:Male,Female,Other',
            'education' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => "required|string|max:20|unique:users,phone,{$user->id}",
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:1000',
        ]);

        // Update User
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->save();

        // Update DoctorDetail
        $doctorDetail->name = $request->input('name');
        $doctorDetail->age = $request->input('age');
        $doctorDetail->gender = $request->input('gender');
        $doctorDetail->education = $request->input('education');
        $doctorDetail->specialty = $request->input('specialty');
        $doctorDetail->phone = $request->input('phone');
        $doctorDetail->address = $request->input('address');

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($doctorDetail->profile_image) {
                Storage::disk('public')->delete($doctorDetail->profile_image);
            }

            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $doctorDetail->profile_image = $imagePath;
        }

        $doctorDetail->save();

        return redirect()->route('doctorDetail.index')->with('success', 'Doctor details updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
