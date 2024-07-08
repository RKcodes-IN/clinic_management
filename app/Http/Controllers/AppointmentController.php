<?php

namespace App\Http\Controllers;

use App\DataTables\AppointmentDataTable;
use App\DataTables\DoctorDetailDataTable;
use App\Models\appointment;
use App\Models\DoctorDetail;
use App\Models\PatientDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AppointmentDataTable $dataTable)
    {
        return $dataTable->render('appointment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = PatientDetail::all();
        $doctors = DoctorDetail::all(); // Fetch all doctors for dropdown
        return view('appointment.create', compact('patients', 'doctors'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'is_new_patient' => 'required',
                'patient_id' => 'required_if:is_new_patient,no',
                'patient_name' => 'required_if:is_new_patient,yes|string',
                'doctor_id' => 'nullable|exists:doctor_details,id',
                'email' => 'required|email',
                'phone_number' => 'required|string',
                'address' => 'required|string',
                'main_complaint' => 'nullable|string',
                'available_date' => 'nullable|date_format:Y-m-d',
                'time_from' => 'nullable|date_format:H:i',
                'time_to' => 'nullable|date_format:H:i|after:time_from',
                'message' => 'nullable|string',
                'status' => 'nullable',
            ]);
            // Begin transaction
            DB::beginTransaction();

            // Initialize variables
            $patientId = null;
            $patientName = null;

            if ($request->input('is_new_patient') == 'yes') {

                // Create new patient if new patient
                $role = Role::where('name', 'patient')->firstOrFail();

                $user = new User();
                $user->name = $request->input('patient_name');
                $user->email = $request->input('email');
                $user->user_role = $role->id;
                $user->phone = $request->input('phone_number');
                $user->save();

                $user->assignRole($role->name);
                $user->syncPermissions($role->permissions->pluck('name'));

                $patientDetails = new PatientDetail();
                $patientDetails->user_id = $user->id;
                $patientDetails->name = $user->name;
                $patientDetails->phone_number = $request->input('phone_number');
                $patientDetails->address = $request->input('address');
                $patientDetails->status = PatientDetail::ACTIVE;
                $patientDetails->save();

                $patientId = $patientDetails->id;
                $patientName = $patientDetails->name;
            } elseif ($request->input('is_new_patient') == 'no') {
                // Existing patient selected
                $patientId = $request->input('patient_id');
                $patientName = $request->input('patient_name');
            }
            // Save appointment
            $appointment = new Appointment();
            $appointment->patient_id = $patientId;
            $appointment->doctor_id = $request->input('doctor_id');
            $appointment->email = $request->input('email');
            $appointment->phone_number = $request->input('phone_number');
            $appointment->address = $request->input('address');
            $appointment->is_previous_report_available = $request->input('is_previous_report_available', false);
            $appointment->main_complaint = $request->input('main_complaint');
            $appointment->available_date = $request->input('available_date');
            $appointment->time_from = $request->input('time_from');
            $appointment->time_to = $request->input('time_to');
            $appointment->message = $request->input('message');
            $appointment->status = $request->input('status');

            if ($appointment->save()) {
                DB::commit();

                return redirect()->route('appointments.create')->with('success', 'Appointment created successfully.');
            } else {
                return redirect()->route('appointments.create')->with('error', 'Something went wrong');
            }

            // Commit transaction

        } catch (\Exception $e) {
            // Rollback transaction if an exception occurs
            DB::rollBack();

            // You can log the error here if needed
            // Log::error($e);

            // Redirect back with error message
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create appointment. Please try again later.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $patients = PatientDetail::all();
        $doctors = DoctorDetail::all();

        return view('appointment.edit', compact('appointment', 'patients', 'doctors'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'is_new_patient' => 'required|in:yes,no',
            'patient_name' => 'required_if:is_new_patient,yes|string|max:255',
            'patient_id' => 'required_if:is_new_patient,no|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string',
            'is_previous_report_available' => 'nullable|boolean',
            'main_complaint' => 'required|string',
            'available_date' => 'required|date',
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i|after:time_from',
            'message' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_keys(Appointment::getStatusLabels())),
        ]);

        $appointment->update([
            'is_new_patient' => $request->is_new_patient,
            'patient_name' => $request->is_new_patient === 'yes' ? $request->patient_name : null,
            'patient_id' => $request->is_new_patient === 'no' ? $request->patient_id : null,
            'doctor_id' => $request->doctor_id,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'is_previous_report_available' => $request->has('is_previous_report_available') ? 1 : 0,
            'main_complaint' => $request->main_complaint,
            'available_date' => $request->available_date,
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'message' => $request->message,
            'status' => $request->status,
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(appointment $appointment)
    {
        //
    }
}
