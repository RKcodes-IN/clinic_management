<?php

namespace App\Http\Controllers;

use App\Models\UpdatedPatientDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdatedPatientDetailController extends Controller
{
    public function index()
    {
        $patients = UpdatedPatientDetail::all();
        return view('updated_patient_details.index', compact('patients'));
    }

    // Method for the full admin form
    public function create()
    {
        return view('updated_patient_details.create');
    }

    // New method for the public form. The patient_id parameter is optional.
    public function createPublicForm($patient_id = null)
    {
        return view('updated_patient_details.public_form', compact('patient_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // For both admin and public forms, patient_id is now optional.
            'patient_id'     => 'nullable|exists:patient_details,id',
            'name'           => 'required|string|max:255',
            'age'            => 'required|integer',
            'dob'            => 'nullable|date',
            'email'          => 'required|email',
            'country_code'   => 'required|string|max:10',
            'phone_number1'  => 'required|string|max:20',
            'alt_contact'    => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'city'           => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'pincode'        => 'nullable|string|max:20',
            'image'          => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('patient_images', 'public');
            $validated['image'] = $imagePath;
        }

        UpdatedPatientDetail::create($validated);

        return view('updated_patient_details.thankyou')->with('message', 'Thank you for updating your details. We will review your submission and update you soon.');
    }

    public function updatePatientDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id'       => 'nullable',
            'name'             => 'required|string|max:255',
            'age'              => 'required|integer',
            'dob'              => 'nullable|date',
            'email'            => 'required|email',
            'country_code'     => 'required|string|max:10',  // primary country code
            'phone_number1'    => 'required|string|max:20',
            'alt_country_code' => 'nullable|string|max:10',  // alternate country code
            'alt_contact'      => 'nullable|string|max:20',
            'casesheet_available'      => 'nullable|string|max:20',
            'consulting_since'      => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'city'             => 'required|string|max:255',
            'country'          => 'required|string|max:255',
            'pincode'          => 'nullable|string|max:20',
            'image'            => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('patient_images', 'public');
        }

        UpdatedPatientDetail::create($data);

        return response()->json([
            'message' => 'Thank you for updating your details. We will review your submission and update you soon.'
        ], 200);
    }



    public function show(UpdatedPatientDetail $updatedPatientDetail)
    {
        return view('updated_patient_details.show', compact('updatedPatientDetail'));
    }

    public function edit(UpdatedPatientDetail $updatedPatientDetail)
    {
        return view('updated_patient_details.edit', compact('updatedPatientDetail'));
    }

    public function update(Request $request, UpdatedPatientDetail $updatedPatientDetail)
    {
        $validated = $request->validate([
            'patient_id'     => 'nullable|exists:patient_details,id',
            'name'           => 'required|string|max:255',
            'age'            => 'required|integer',
            'dob'            => 'nullable|date',
            'email'          => 'required|email|unique:updated_patient_details,email,' . $updatedPatientDetail->id,
            'country_code'   => 'required|string|max:10',
            'phone_number1'  => 'required|string|max:20',
            'alt_contact'    => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'city'           => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'pincode'        => 'nullable|string|max:20',
            'image'          => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('patient_images', 'public');
            $validated['image'] = $imagePath;
        }

        $updatedPatientDetail->update($validated);

        return redirect()->route('updated-patient-details.index')
            ->with('success', 'Patient details updated successfully.');
    }

    public function destroy(UpdatedPatientDetail $updatedPatientDetail)
    {
        $updatedPatientDetail->delete();
        return redirect()->route('updated-patient-details.index')
            ->with('success', 'Patient details deleted successfully.');
    }
}
