<?php

namespace App\Http\Controllers;

use App\DataTables\HealthEvaluationDataTable;
use App\Models\DoctorDetail;
use App\Models\HabitVariable;
use App\Models\HealthEvaluation;
use App\Models\PastHistory;
use App\Models\PatientAddication;
use App\Models\PatientDetail;
use App\Models\PatientPastHistory;
use App\Models\PatientSurgicalHistory;
use App\Models\SurgicalVariable;
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
        $surgicalVariable = SurgicalVariable::all();
        $habits = HabitVariable::all();
        // Fetch all doctors for dropdown
        return view('health-evalution.create', compact('patients', 'doctors', 'pastHistory', 'surgicalVariable', 'habits'));
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
            $evaluation->diet_preference = $request->input('diet_preference');
            $evaluation->irregular_mealtime = $request->input('meal_timing');
            $evaluation->irregular_mealtime_if_yes = $request->input('meal_timing_desc');
            $evaluation->exercise = $request->input('exercise');
            $evaluation->yoga = $request->input('yoga');
            $evaluation->duration = $request->input('fitness_duration');
            $evaluation->distance = $request->input('fitness_distance');
            $evaluation->appetite = $request->input('appetite');
            $evaluation->digestion = $request->input('digestion');
            $evaluation->hyper_acidity = $request->input('hyper_acidity');
            $evaluation->urine_times_day = $request->input('urine_day');
            $evaluation->urine_times_night = $request->input('urine_night');
            $evaluation->any_difficulty_urine = $request->input('urine_difficulty');
            $evaluation->sleep_time_from = $request->input('sleep_time_from');
            $evaluation->sleep_time_to = $request->input('sleep_time_to');
            $evaluation->day_sleeping = $request->input('sleep_daytime');
            $evaluation->day_sleep_time_from = $request->input('sleep_daytime_time_from');
            $evaluation->day_sleep_time_to = $request->input('sleep_daytime_time_to');
            $evaluation->diff_in_initation = $request->input('sleep_difficulty_initiation');
            $evaluation->feel_stress = $request->input('stress') ?? "na";
            $evaluation->reason_for_stress = $request->input('stress_reason') ?? "na";
            $evaluation->worry_most = $request->input('stress_worries') ?? "null";

            $evaluation->save();

            // Save patient past history

            foreach ($request["past_histories"] as $history) {

                $paitentPastHistroty = new PatientPastHistory();
                $paitentPastHistroty->patient_id = $patientId;
                $paitentPastHistroty->appointment_id = 0;
                $paitentPastHistroty->evalution_id = $evaluation->id;
                $paitentPastHistroty->past_histroy_id = $history['history_id'];
                $paitentPastHistroty->yes_no = $history['yes_no'] ?? "no";
                $paitentPastHistroty->no_of_years = $history['since'] ?? null;
                $paitentPastHistroty->trade_name = $history['trade_name'] ?? null;
                $paitentPastHistroty->chemical = $history['chemical'] ?? null;
                $paitentPastHistroty->dose_freq = $history['dose_freq'] ?? null;
                $paitentPastHistroty->date = date('Y-m-d');
                $paitentPastHistroty->save();
            }

            // Save surgical history
            foreach ($request["surgical_histories"] as $surgicalHistory) {
                $surgicalHostory = new PatientSurgicalHistory();
                $surgicalHostory->patient_id = $patientId;
                $surgicalHostory->evalution_id = $evaluation->id;
                $surgicalHostory->surgical_variable_id = $surgicalHistory['surgical_id'];
                $surgicalHostory->yes_no = $valueEvalution['yes_no'] ?? "no";
                $surgicalHostory->date = date('Y-m-d');
                $surgicalHostory->save();
            }

            // Save habits/addictions
            foreach ($request["habits"] as $valueHabit) {

                $habit = new PatientAddication();
                $habit->patient_id = $patientId;
                $habit->evalution_id = $evaluation->id;
                $habit->habit_id = $valueHabit['habit_id'];
                $habit->yes_no = $valueHabit['value'] ?? "no";
                $habit->save();
            }

            DB::commit();
            return redirect()->route('healthevalution.index')->with('success', 'Health Evalution Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('healthevalution.create')
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
