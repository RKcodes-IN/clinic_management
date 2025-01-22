<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Item;
use App\Models\LabPrescription;
use App\Models\Stock;
use Illuminate\Http\Request;

class LabPrescriptionController extends Controller
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
    public function create(Request $request)
    {
        $appointmentId = $request->query('appointmentId');
        $patientId = $request->query('patientId');
        // Ensure $appointmentId is provided
        if (empty($appointmentId)) {
            abort(400, 'Appointment ID is required');
        }

        // Fetch pharmacy items that have related stock entries


        // Fetch lab items that have related stock entries
        $labItems = Item::where('item_type', Item::TYPE_LAB)
            ->whereHas('stock') // Check if stock exists using the relationship
            ->get();

        // Pass data to the view
        return view('lab-prescription.create', compact('appointmentId', 'patientId', 'labItems'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'appointment_id' => 'required|integer',
        ]);

        // Fetch patient ID
        $appointment = Appointment::find($validated['appointment_id']);
        if (!$appointment) {
            return redirect()->back()->withErrors('Appointment not found.');
        }
        $patientId = $appointment->patient_id;
        if ($request->has('labtest')) {
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LabPrescription $labPrescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LabPrescription $labPrescription)
    {
        //
    }
}
