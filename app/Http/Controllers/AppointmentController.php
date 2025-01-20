<?php

namespace App\Http\Controllers;

use App\DataTables\AppointmentDataTable;
use App\DataTables\AppointmentswaDataTable;
use App\DataTables\DoctorDetailDataTable;
use App\Imports\AppointmentImport;
use App\Models\Appointment;
use App\Models\DoctorDetail;
use App\Models\HealthEvaluation;
use App\Models\Item;
use App\Models\PatientDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AppointmentDataTable $dataTable)
    {
        $items = Item::all();
        return $dataTable->render('appointment.index', compact('items'));
    }
    public function AppointmentWa(AppointmentswaDataTable $dataTable)
    {
        return $dataTable->render('appointment.appointmentwa');
    }

    public function patientDetails($id = '')
    {
        $data = [];




        $patientDetails = PatientDetail::find($id);
        $patientUser = User::find($patientDetails->user_id);
        $arrayMerge = array_merge($patientDetails->toArray(), $patientUser->toArray());
        if ($arrayMerge) {
            $data["success"] = true;
            $data["details"] = $arrayMerge;
        } else {
            $data["success"] = false;
            $data["error"] = "patient not found";
        }
        return json_encode($data);
    }
    /**
     * Show the form for creating a new resource.
     */

    public function search(Request $request)
    {
        // Validate that the query is provided


        $query = $request->input('query');
        // var_dump($query);
        // exit;
        // Search patients based on the query and eager load 'user' relationship
        $patients = PatientDetail::where('name', 'LIKE', "%{$query}%")
            ->with('user') // Include related user data
            ->get();

        // Return the mapped patients with user info, handling null user
        return response()->json($patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => optional($patient->user)->email, // Handle null user
                'phone_number' => $patient->phone_number,
                'place' => $patient->place ?? "",
            ];
        }));
    }

    public function create()
    {
        $patients = PatientDetail::all();
        $doctors = DoctorDetail::all();
        // Fetch all doctors for dropdown
        return view('appointment.create', compact('patients', 'doctors'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);

        // Validate the input data
        $request->validate([
            'patient_id' => 'nullable',
            'new_patient_name' => 'nullable|string|max:255',
            'doctor_id' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'main_complaint' => 'required|string',
            'available_date' => 'required|date',
            'time_from' => 'required',
            'time_to' => 'required',
            'patient_type' => 'required|in:new,old', // Ensure patient type is selected
        ]);

        DB::beginTransaction();

        try {
            $patientId = null;

            // Check if it's a new patient
            if ($request->input('patient_type') === 'new' && $request->filled('new_patient_name')) {
                // Create a new patient
                $role = Role::where('name', 'patient')->firstOrFail();
                $existingUser = User::where('email', $request->input('email'))->first();

                if ($existingUser) {
                    DB::rollBack();
                    return redirect()->route('appointments.create')
                        ->with('error', 'Email already taken.')
                        ->with('toast', 'error');
                }

                // Create a new user
                $user = new User();
                $user->name = $request->input('new_patient_name');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone_number');
                $user->user_role = $role->id;
                $user->save();

                $user->assignRole($role->name);
                $user->syncPermissions($role->permissions->pluck('name'));

                // Create new patient details
                $patient = new PatientDetail();
                $patient->name = $request->input('new_patient_name');
                $patient->user_id = $user->id;
                $patient->phone_number = $request->input('phone_number');
                $patient->address = $request->input('address');
                $patient->gender = $request->input('gender');
                $patient->date_of_birth = $request->input('gender');
                $patient->status = PatientDetail::ACTIVE;
                $patient->save();

                $patientId = $patient->id;
            } elseif ($request->input('patient_type') === 'old' && $request->filled('patient_id')) {
                //
                // dd($request->input('age'));

                // Use existing patient if old patient is selected
                $patientId = $request->input('patient_id');

                if ($patientId < 1) {
                    return redirect()->back()
                        ->with('error', 'Invalid patient ID provided.')
                        ->with('toast', 'error');
                }
            }

            // Ensure patient ID is valid
            if (is_null($patientId) || empty($patientId) || $patientId < 1) {
                return redirect()->route('appointments.create')
                    ->with('error', 'Missing or invalid patient ID. Please refresh and try again.')
                    ->with('toast', 'error');
            }

            // Create appointment
            $appointment = new Appointment();
            $appointment->patient_id = $patientId;
            $appointment->doctor_id = $request->input('doctor_id');
            $appointment->email = $request->input('email');
            $appointment->phone_number = $request->input('country_code') . $request->input('phone_number');
            $appointment->address = $request->input('address');
            $appointment->is_previous_report_available = $request->input('is_previous_report_available', false);
            $appointment->main_complaint = $request->input('main_complaint');
            $appointment->available_date = $request->input('available_date');
            $appointment->time_from = $request->input('time_from');
            $appointment->time_to = $request->input('time_to');
            $appointment->confirmation_date = $request->input('available_date');
            $appointment->country = $request->input('country');
            $appointment->city = $request->input('city');
            $appointment->confirmation_time = $request->input('time_from');
            $appointment->message = $request->input('message');
            $appointment->age = $request->input('age');
            $appointment->status = $request->input('status', 'pending'); // Default status
            $appointment->type = $request->input('type'); // Default status
            $appointment->is_online = $request->input('is_online'); // Default status
            $appointment->save();

            DB::commit();

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment created successfully.')
                ->with('toast', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('appointments.create')
                ->with('error', 'Something went wrong: ' . $e->getMessage())
                ->with('toast', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);
        // $healthEvalutions = HealthEvaluation::where('appontment_id', $id)->get();
        return view('appointment.show', compact('appointment',));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {

        $id = $request->get('id');;
        $appointment = Appointment::findOrFail($id);
        $patients = PatientDetail::all();
        $doctors = DoctorDetail::all();

        return view('appointment.update', compact('appointment', 'patients', 'doctors'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {

        $appointment = Appointment::findOrFail($request->id);

        $appointment->update([
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

    public function calender()
    {
        return view('appointment.calander');
    }

    // Add the following methods to your controller

    public function calendar()
    {
        // Get the authenticated doctor
        $doctor = Auth::user();
        $doctorDetail = DoctorDetail::where('user_id', $doctor->id)->firstOrFail();

        // Get appointments for the authenticated doctor
        $appointments = Appointment::where('doctor_id', $doctorDetail->id)
            ->with('patient')  // Ensure patient details are loaded
            ->get()
            ->map(function ($appointment) {
                return [
                    'title' => 'Appointment with ' . ($appointment->patient->name ?? 'Unknown Patient'),
                    'start' => $appointment->available_date . 'T' . $appointment->time_from,
                    'end' => $appointment->available_date . 'T' . $appointment->time_to,
                    'extendedProps' => [
                        'email' => $appointment->patient->email ?? 'N/A',
                        'phone_number' => $appointment->patient->phone_number ?? 'N/A',
                        'message' => $appointment->message ?? 'No message',
                    ],
                ];
            });

        return response()->json($appointments);
    }


    public function calendarView()
    {
        // Get the authenticated doctor
        $doctor = Auth::user();

        // Return the view with doctor data (optional for personalization)
        return view('appointment.calander', ['doctor' => $doctor]);
    }



    public function approve(Request $request, $id)
    {
        $request->validate([
            'approve_date' => 'required|date',
            'slot_time' => 'required|date_format:H:i',
        ]);

        // Approve logic here
        $appointment = Appointment::findOrFail($id);
        $appointment->confirmation_date = $request->approve_date;
        $appointment->confirmation_time = $request->slot_time;
        $appointment->status = Appointment::STATUS_CONFIRMED;
        $appointment->save();

        return response()->json(['success' => true, 'message' => 'Appointment approved successfully']);
    }

    public function reject($id)
    {
        // Reject logic here
        $appointment = Appointment::findOrFail($id);
        $appointment->status = Appointment::STATUS_CANCELLED;
        $appointment->save();

        return response()->json(['success' => true, 'message' => 'Appointment rejected successfully']);
    }

    public function importForm(Request $request)
    {
        return view('appointment.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv', // Validate file type
        ]);

        // Import the Excel file
        Excel::import(new AppointmentImport, $request->file('file'));

        return redirect()->back()->with('success', 'Import Form');
    }
}
