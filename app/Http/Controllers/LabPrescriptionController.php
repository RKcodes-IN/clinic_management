<?php

namespace App\Http\Controllers;

use App\DataTables\LabPrescriptionDataTable;
use App\Models\Appointment;
use App\Models\Item;
use App\Models\LabPrescription;
use App\Models\PatientDetail;
use App\Models\SampleType;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabPrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LabPrescriptionDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('lab-prescription.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $appointmentId = $request->query('appointmentId');
        $patientId = $request->query('patientId');
        // Ensure $appointmentId is provided
        if (!empty($patientId)) {
            $patientDetails = PatientDetail::find($patientId);
        } else {
            return redirect()->back()->with('error', 'Patient Id is required.');
        }
        $labItems = Item::where('item_type', Item::TYPE_LAB)
            ->whereHas('stock') // Check if stock exists using the relationship
            ->get();


        return view('lab-prescription.create', compact('appointmentId', 'patientId', 'labItems', 'patientDetails'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'appointment_id' => 'integer',
            'patient_id' => 'required'
        ]);

        // Fetch patient ID
        if (!empty($validated['appointment_id'])) {
            $appointment_id = 0;
        }


        $patientId = $validated['patient_id'];
        if ($request->has(key: 'labtest')) {
            foreach ($request->labtest as $labItem) {
                $stockId = null; // Initialize stockId
                $stocks = Stock::where('item_id', $labItem['item'])->get();

                if ($stocks->isNotEmpty()) {
                    foreach ($stocks as $stock) {
                        // Get available quantity of the stock
                        $getAvailableQuantity = Stock::getTotalStock($stock->id);

                        if ($getAvailableQuantity > 0) {
                            $stockId = $stock->id;
                            break; // Exit loop as soon as valid stock is found
                        } else {
                            $stockId = $stock->id;
                        }
                    }
                }

                LabPrescription::create([
                    'patient_id' => $patientId,
                    'appointment_id' => $validated['appointment_id'],
                    'item_id' => $labItem['item'],
                    'stock_id' => $stockId,
                    'quantity' => 1,
                    'sample_type_id' => 0,
                    'sample_taken' => "no",
                    'report_available' => "no",
                    'report_url' => null,
                    'out_of_range' => "no",
                    'status' => 1,
                    'date' => date('Y-m-d'), // Can be null if no valid stock is found
                    'description' => $labItem['message'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Prescriptions saved successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(LabPrescription $labPrescription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LabPrescription $labPrescription)
    {
        $sampleTypes = SampleType::all();
        $labpres = LabPrescription::find($labPrescription->id);

        $labPrescriptions = LabPrescription::where('date', $labpres->date)->all();
        return view('lab-prescription.update', compact('labPrescription', 'sampleTypes', 'labPrescriptions'));
    }

    public function update(Request $request, LabPrescription $labPrescription)
    {
        $request->validate([
            'sample_type_id' => 'required|exists:sample_types,id',
            'sample_taken' => 'sometimes|boolean',
            'report_available' => 'sometimes|boolean',
            'report' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
        ]);

        $data = [
            'sample_type_id' => $request->sample_type_id,
            'sample_taken' => $request->has('sample_taken'),
            'report_available' => $request->has('report_available'),
        ];

        if ($request->hasFile('report')) {
            // Delete old report if exists
            if ($labPrescription->report_path) {
                Storage::delete($labPrescription->report_path);
            }

            $path = $request->file('report')->store('reports');
            $data['report_path'] = $path;
        }

        $labPrescription->update($data);

        return redirect()->route('lab-prescription.index')
            ->with('success', 'Lab prescription updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LabPrescription $labPrescription)
    {
        //
    }
}
