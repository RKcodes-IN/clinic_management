<?php

namespace App\Http\Controllers;

use App\DataTables\PaitentDetailDataTable;
use App\Imports\PatientsImport;
use App\Imports\SendInteraktMessageImport;
use App\Models\Appointment;
use App\Models\HealthEvaluation;
use App\Models\InvestigationReport;
use App\Models\LabPrescription;
use App\Models\patient_detail;
use App\Models\PatientDetail;
use App\Models\PharmacyPrescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class PatientDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaitentDetailDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('paitentdetail.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paitentdetail.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // Controller method to show the create form


    // Controller method to store the patient details
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:users,phone',
            'date_of_birth' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        // Set role to "patient"
        $role = Role::where('name', 'patient')->firstOrFail();

        // Create User
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('phone_number') . '@example.com'; // Placeholder email using phone number
        $user->password = Hash::make($request->input('phone_number')); // Password set as phone number
        $user->phone = $request->input('phone_number');
        $user->user_role = $role->id;
        $user->save();

        $user->assignRole($role->name);
        $user->syncPermissions($role->permissions->pluck('name'));

        // Create PatientDetail
        $patientDetail = new PatientDetail();
        $patientDetail->user_id = $user->id;
        $patientDetail->name = $request->input('name');
        $patientDetail->phone_number = $request->input('phone_number');
        $patientDetail->date_of_birth = $request->input('date_of_birth');
        $patientDetail->address = $request->input('address');
        $patientDetail->status = $request->input('status');
        $patientDetail->age = $request->input('age');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('patient_images', 'public');
            $patientDetail->image = $imagePath;
        }

        $patientDetail->save();

        return redirect()->route('paitent.index')->with('success', 'Patient and Patient Detail created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paitent = PatientDetail::findOrFail($id);

        $healthEvalutions = HealthEvaluation::where('patient_id', $paitent->id)->get();

        $appontments = Appointment::with(['doctor'])->where('patient_id', $id)->get();
        $investigationReport =
            InvestigationReport::with(['reportTypeValues.reportType'])
            ->where('patient_id', $id)
            ->get();

        $appontmentCount = Appointment::where('patient_id', $id)->count();
        $pharmacyPrescriptions = PharmacyPrescription::where('patient_id', $id)->get();
        $labPrescriptions = LabPrescription::where('patient_id', $id)->get();

        return view('paitentdetail.show', compact('paitent', 'healthEvalutions', 'appontments', 'investigationReport', 'appontmentCount', 'pharmacyPrescriptions', 'labPrescriptions'));
    }

    public function updateForm()
    {
        return view('paitentdetail.updateform');
    }
    public function sendUpdateForm(Request $request)
    {

        ini_set('max_execution_time', 800);

        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        Excel::import(new SendInteraktMessageImport, $request->file('file'));

        return back()->with('success', 'Message Send Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $patientDetail = PatientDetail::findOrFail($id);
        return view('paitentdetail.edit', compact('patientDetail'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'phone_number' => 'required|string|max:20|unique:users,phone,' . $id,
            'date_of_birth' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        // Find the PatientDetail record
        $patientDetail = PatientDetail::findOrFail($id);

        // Update User record
        $user = $patientDetail->user;
        $user->name = $request->input('name');
        $user->phone = $request->input('phone_number');
        $user->save();

        // Update PatientDetail record
        $patientDetail->name = $request->input('name');
        $patientDetail->phone_number = $request->input('phone_number');
        $patientDetail->date_of_birth = $request->input('date_of_birth');
        $patientDetail->address = $request->input('address');
        $patientDetail->status = $request->input('status');

        // Handle image upload and replace if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($patientDetail->image) {
                Storage::disk('public')->delete($patientDetail->image);
            }
            $imagePath = $request->file('image')->store('patient_images', 'public');
            $patientDetail->image = $imagePath;
        }

        $patientDetail->save();

        return redirect()->route('paitent.index')->with('success', 'Patient details updated successfully.');
    }
    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, patient_detail $patient_detail)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function importForm()
    {
        return view('paitentdetail.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new PatientsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Patients imported successfully!');
    }
}
