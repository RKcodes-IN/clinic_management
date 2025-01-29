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
        $labPrescriptions = LabPrescription::where('date', $labPrescription->date)
            ->where('patient_id', $labPrescription->patient_id)
            ->with('item', 'sampleType', 'patient') // Ensure patient relationship is loaded
            ->get();

        return view('lab-prescription.update', compact('sampleTypes', 'labPrescriptions', 'labPrescription'));
    }

    public function update(Request $request, LabPrescription $labPrescription)
    {
        $validatedData = $request->validate([
            'lab_prescriptions.*.sample_type_id' => 'required|exists:sample_types,id',
            'lab_prescriptions.*.sample_taken' => 'sometimes|boolean',
            'lab_prescriptions.*.report_available' => 'sometimes|boolean',
            'lab_prescriptions.*.value' => 'sometimes|string', // New field
            'lab_prescriptions.*.out_of_range' => 'sometimes|boolean', // New field
            'lab_prescriptions.*.report' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
        ]);

        foreach ($request->lab_prescriptions as $id => $data) {
            $labPrescription = LabPrescription::findOrFail($id);

            $updateData = [
                'sample_type_id' => $data['sample_type_id'],
                'sample_taken' => isset($data['sample_taken']),
                'report_available' => isset($data['report_available']),
                'value' => isset($data['value']), // New field
                'out_of_range' => isset($data['out_of_range']), // New field
            ];


            if ($request->hasFile("lab_prescriptions.{$id}.report")) {
                $file = $request->file("lab_prescriptions.{$id}.report");
                if ($labPrescription->report_path) {
                    Storage::delete($labPrescription->report_path);
                }
                $path = $file->store('reports');
                $updateData['report_path'] = $path;
            }

            $labPrescription->update($updateData);
        }

        return redirect()->route('labprescription.index')
            ->with('success', 'All lab prescriptions updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LabPrescription $labPrescription)
    {
        //
    }
}
