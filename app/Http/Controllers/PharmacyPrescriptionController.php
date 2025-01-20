<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Item;
use App\Models\LabPrescription;
use App\Models\PharmacyPrescription;
use App\Models\Stock;
use Illuminate\Http\Request;

class PharmacyPrescriptionController extends Controller
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
        $pharmacyItems = Item::where('item_type', Item::TYPE_PHARMACY)
            ->whereHas('stock') // Check if stock exists using the relationship
            ->get()
            ->map(function ($item) {
                $item->total_stock = Stock::getTotalStockByItem($item->id); // Calculate total stock
                return $item;
            });

        // Fetch lab items that have related stock entries
        $labItems = Item::where('item_type', Item::TYPE_LAB)
            ->whereHas('stock') // Check if stock exists using the relationship
            ->get();

        // Pass data to the view
        return view('pharmacy-prescription.create', compact('appointmentId', 'patientId', 'pharmacyItems', 'labItems'));
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

        // Save Pharmacy Prescriptions
        if ($request->has('pharmacy')) {
            foreach ($request->pharmacy as $pharmacyItem) {
                $stockId = null; // Initialize stockId
                $stocks = Stock::where('item_id', $pharmacyItem['item'])->get();

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

                PharmacyPrescription::create([
                    'patient_id' => $patientId,
                    'appointment_id' => $validated['appointment_id'],
                    'item_id' => $pharmacyItem['item'],
                    'stock_id' => $stockId, // Can be null if no valid stock is found
                    'quantity' => $pharmacyItem['quantity'],
                    'description' => $pharmacyItem['message'] ?? null,
                ]);
            }
        }

        // Save Lab Test Prescriptions
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
                    'stock_id' => $stockId, // Can be null if no valid stock is found
                    'quantity' => $labItem['quantity'],
                    'description' => $labItem['message'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Prescriptions saved successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(PharmacyPrescription $pharmacyPrescription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PharmacyPrescription $pharmacyPrescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PharmacyPrescription $pharmacyPrescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PharmacyPrescription $pharmacyPrescription)
    {
        //
    }
}
