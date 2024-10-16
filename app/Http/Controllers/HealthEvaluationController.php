<?php

namespace App\Http\Controllers;

use App\DataTables\HealthEvaluationDataTable;
use App\Models\DoctorDetail;
use App\Models\HealthEvaluation;
use App\Models\PastHistory;
use App\Models\PatientAddication;
use App\Models\PatientDetail;
use App\Models\PatientPastHistory;
use App\Models\PatientSurgicalHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class HealthEvaluationController extends Controller
{
    public function index(HealthEvaluationDataTable $dataTable)
    {
        return $dataTable->render('health-evalution.index');
    }
    public function create()
    {
        $patients = PatientDetail::all();
        $doctors = DoctorDetail::all();
        $pastHistory = PastHistory::all();
        // Fetch all doctors for dropdown
        return view('health-evalution.create', compact('patients', 'doctors', 'pastHistory'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $request->validate([
            'patient_id' => 'nullable',
            'new_patient_name' => 'nullable|string|max:255',

            'history.*.yes_no' => 'nullable|in:yes,no',
            'history.*.since' => 'nullable|string',
            'history.*.trade_name' => 'nullable|string',
            'history.*.chemical' => 'nullable|string',
            'history.*.dose_freq' => 'nullable|string',
            'habit.*' => 'required|in:yes,no',
            'surgery.*' => 'required|in:yes,no',
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
                $patient->date_of_birth = $request->input('date_of_birth'); // fixed field for date_of_birth
                $patient->status = PatientDetail::ACTIVE;
                $patient->save();

                $patientId = $patient->id;
            } elseif ($request->filled('patient_id')) {
                // Use existing patient
                $patientId = $request->input('patient_id');
            }

            // Ensure patient ID is not null
            if (is_null($patientId)) {
                throw new \Exception("Patient ID is missing.");
            }

            // Create appointment evaluation
            $evaluation = new HealthEvaluation();
            $evaluation->patient_id = $patientId;
            $evaluation->appointment_id = 0;
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

            // Save patient past history
            foreach ($request["history"] as $id => $value) {
                $paitentPastHistroty = new PatientPastHistory();
                $paitentPastHistroty->patient_id = $patientId;
                $paitentPastHistroty->appointment_id = 0;
                $paitentPastHistroty->evalution_id = $evaluation->id;
                $paitentPastHistroty->past_histroy_id = $id;
                $paitentPastHistroty->yes_no = $value['yes_no'] ?? null;
                $paitentPastHistroty->no_of_years = $value['since'] ?? null;
                $paitentPastHistroty->trade_name = $value['trade_name'] ?? null;
                $paitentPastHistroty->chemical = $value['chemical'] ?? null;
                $paitentPastHistroty->dose_freq = $value['dose_freq'] ?? null;
                $paitentPastHistroty->date = date('Y-m-d');
                $paitentPastHistroty->save();
            }

            // Save surgical history
            foreach ($request["surgery"] as $name => $valueEvalution) {
                $surgicalHostory = new PatientSurgicalHistory();
                $surgicalHostory->patient_id = $patientId;
                $surgicalHostory->evalution_id = $evaluation->id;
                $surgicalHostory->name = $name;
                $surgicalHostory->yes_no = $valueEvalution ?? null;
                $surgicalHostory->date = date('Y-m-d');
                $surgicalHostory->save();
            }

            // Save habits/addictions
            foreach ($request["habit"] as $habitName => $valueHabit) {
                $habit = new PatientAddication();
                $habit->patient_id = $patientId;
                $habit->evalution_id = $evaluation->id;
                $habit->name = $habitName;
                $habit->yes_no = $valueHabit ?? null;
                $habit->save();
            }

            DB::commit();
            dd("success");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('appointments.create')
                ->with('error', 'Something went wrong: ' . $e->getMessage())
                ->with('toast', 'error');
        }
    }

    public function show($id)
    {
        // Fetch the health evaluation by ID
        $healthEvaluation = HealthEvaluation::findOrFail($id);

        // Fetch the patient details based on the patient ID in the health evaluation
        $paitent = PatientDetail::findOrFail($healthEvaluation->patient_id);

        // Fetch the related past histories and group them by date
        $pastHistories = PatientPastHistory::with('pastHistory')
            ->where('evalution_id', $healthEvaluation->id)
            ->get()
            ->groupBy('date'); // Group by date

        $surgicalHistories = PatientSurgicalHistory::where('evalution_id', $healthEvaluation->id)
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
            });
        $addications = PatientAddication::where('evalution_id', $healthEvaluation->id)
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
            });
        // Pass all the fetched data to the view
        return view('health-evalution.show', compact('paitent', 'healthEvaluation', 'pastHistories', 'surgicalHistories', 'addications'));
    }
}
