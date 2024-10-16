<?php

namespace App\Http\Controllers;

use App\DataTables\AppointmentDataTable;
use App\DataTables\DoctorDetailDataTable;
use App\Models\Appointment;
use App\Models\DoctorDetail;
use App\Models\HealthEvaluation;
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
        ]);

        DB::beginTransaction();

        try {
            $patientId = null;

            // Check if new patient name is provided
            if ($request->filled('new_patient_name')) {
                // Create new patient
                $role = Role::where('name', 'patient')->firstOrFail();
                $existingUser = User::where('email', $request->input('email'))->first();

                if ($existingUser) {
                    DB::rollBack();
                    return redirect()->route('appointments.create')
                        ->with('error', 'Email already taken.')
                        ->with('toast', 'error');  // Added toast message
                }

                // Create new user
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
            } elseif ($request->filled('patient_id')) {
                // Use existing patient
                $patientId = $request->input('patient_id');
            }

            // Ensure patient ID is not null
            if (is_null(value: $patientId)) {
                throw new \Exception("Patient ID is missing.");
            }

            // Create appointment
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
            $appointment->status = $request->input('status', 'pending'); // Default status
            $appointment->save();

            // Ensure patient object exists for evaluation
            if (isset($patient)) {
                $evaluation = new HealthEvaluation();
                $evaluation->patient_id = $patient->id;
                $evaluation->appointment_id = $appointment->id;
                $evaluation->age = $request->input('age');
                $evaluation->weight = $request->input('weight');
                $evaluation->height = $request->input('height');
                $evaluation->occupation = $request->input('occupation');
                $evaluation->gender = $request->input('gender');
                $evaluation->working_hours = $request->input('working_hours');
                $evaluation->night_shift = $request->input('night_shift', false);
                $evaluation->climatic_condition = $request->input('climatic_condition');
                $evaluation->allergic_to_drugs = $request->input('allergic_to_any_drugs', false);
                $evaluation->allergic_drug_names = $request->input('allergic_drug_names');
                $evaluation->food_allergies = $request->input('food_allergies');
                $evaluation->lactose_tolerance = $request->input('tolerance_to_lactose');
                $evaluation->lmp = $request->input('lmp');
                $evaluation->save();
            }

            DB::commit();
            // flash()->success('Appointment created successfully.');

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment created successfully.')
                ->with('toast', 'success');  // Added success toast

        } catch (\Exception $e) {
            DB::rollBack();
            // flash()->error('Something went wrong');

            return redirect()->route('appointments.create')
                ->with('error', 'Something went wrong: ' . $e->getMessage())
                ->with('toast', 'error');  // Added error toast
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
}
