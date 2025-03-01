<?php

namespace App\Http\Controllers;

use App\DataTables\TherapyDataTable;
use App\Models\Item;
use App\Models\PatientDetail;
use App\Models\Therapy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TherapyController extends Controller
{
    public function index(TherapyDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->render('therapy.index');
    }
    public function create(Request $request)
    {
        // Get appointment and patient IDs from the query parameters
        $appointmentId = $request->query(key: 'appointmentId');
        $patientId = $request->query('patientId');

        // Ensure patient ID is provided
        if (!empty($patientId)) {
            $patientDetails = PatientDetail::find($patientId);
        } else {
            return redirect()->back()->with('error', value: 'Patient Id is required.');
        }

        // Fetch therapy items (assuming Item::TYPE_THERAPY is defined, e.g., 'therapy')
        $therapyItems = Item::where('item_type', Item::TYPE_THERAPY)->get();

        // Return the therapy create view with necessary data
        return view('therapy.create', compact('appointmentId', 'patientId', 'therapyItems', 'patientDetails'));
    }

    /**
     * Store therapy prescriptions in the database.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'appointment_id' => 'integer',
            'patient_id' => 'required'
        ]);

        $patientId = $validated['patient_id'];
        $appointmentId = $validated['appointment_id'] ?? 0; // Default to 0 if not provided

        // Process therapy items if provided
        if ($request->has('therapy')) {
            foreach ($request->therapy as $therapyItem) {
                Therapy::create([
                    'patient_id' => $patientId,
                    'appointment_id' => $appointmentId,
                    'item_id' => $therapyItem['item'],
                    'sub_category' => $therapyItem['sub_category'],
                    'status' => 'pending', // Default status (adjust as needed)
                ]);
            }
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Therapies saved successfully.');
    }

    public function edit(Therapy $therapy)
    {
        $therapies= Therapy::where('created_at', $therapy->created_at)
            ->where('patient_id', $therapy->patient_id)
            ->with('item', 'patient') // Ensure patient relationship is loaded
            ->get();

        return view('therapy.update', compact('therapies', 'therapy'));
    }

    public function update(Request $request)
    {
        $therapiesData = $request->input('therapies');

        foreach ($therapiesData as $id => $data) {
            $therapy = Therapy::findOrFail($id);
            $therapy->update([
                'material' => $data['material'],
                'application_area' => $data['application_area'],
                'time_from' => $data['time_from'],
                'time_to' => $data['time_to'],
                'status' => isset($data['completed']) ? true : false,
            ]);
        }

        return redirect()->route('therapy.index')->with('success', 'Therapies updated successfully.');
    }


    public function show(Therapy $Therapy)
    {
        return view('therapy.show', compact('surgicalVariable'));
    }
}
