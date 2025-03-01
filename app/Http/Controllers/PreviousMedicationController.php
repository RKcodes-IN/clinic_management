<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use App\Models\PreviousMedication;
use Illuminate\Http\Request;

class PreviousMedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($patient_id, $appointment_id)
    {
        // Pass patient_id and appointment_id from GET parameters (route)
        return view('previous_medications.create', compact('patient_id', 'appointment_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $patient_id, $appointment_id)
    {
        $request->validate([
            'medications' => 'required|array',
            'medications.*.medicine_name' => 'required|string|max:255',
            'medications.*.chemical_id' => 'required|string|max:255',
        ]);

        foreach ($request->medications as $medication) {
            // Find or create the chemical based on its code.
            // If you have additional attributes to set when creating a new chemical, you can add them as the second parameter.
            $chemical = Chemical::firstOrCreate(
                ['name' => $medication['chemical_id']]
            );

            PreviousMedication::create([
                'patient_id'    => $patient_id,
                'appointment_id' => $appointment_id,
                'medicine_name' => $medication['medicine_name'],
                // Store the actual chemical ID from the chemicals table
                'chemical_id'   => $chemical->id,
            ]);
        }

        return redirect()->back()->with('success', 'Previous medications added successfully.');
    }

    public function searchChemicals(Request $request)
    {
        $query = $request->get('q');
        $chemicals = Chemical::where('name', 'like', "%$query%")->get();
        return response()->json($chemicals->map(function ($chemical) {
            return ['id' => $chemical->name, 'text' => $chemical->name];
        }));
    }
    /**
     * Display the specified resource.
     */
    public function show(PreviousMedication $previousMedication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PreviousMedication $previousMedication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PreviousMedication $previousMedication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PreviousMedication $previousMedication)
    {
        //
    }
}
