<?php

namespace App\Http\Controllers;

use App\Models\InvestigationReport;
use App\Models\InvestigationReportType;
use App\Models\InvestigationReportValues;
use App\Models\PatientDetail;
use Illuminate\Http\Request;

class InvestigationReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InvestigationReportType $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('investigationreport.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = PatientDetail::all();
        $investigationReportType = InvestigationReportType::all();
        return view('investigationreport.create', compact('patients', 'investigationReportType'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validate the main investigation report and dynamic arrays
        // $request->validate([
        //     'patient_id' => 'required|exists:patient_details,id',
        //     'report_date' => 'required|date',
        //     'report_url' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        //     'report_types' => 'required|array',
        //     'report_types.*' => 'exists:investigation_report_types,id', // Validate each report type
        //     'values' => 'nullable|array',
        //     'values.*' => 'nullable|string',
        //     'out_of_range' => 'nullable|array',
        //     'out_of_range.*' => 'nullable|string|in:on', // Must be 'on' if present
        // ]);

        // Handle file upload
        $filePath = $request->file('report_url')->store('investigation_reports', 'public');

        // Save the main investigation report
        $investigationReport = new InvestigationReport();
        $investigationReport->patient_id = $request->input('patient_id');
        $investigationReport->report_date = $request->input('report_date');
        $investigationReport->report_url = $filePath;
        $investigationReport->save();

        // Save the investigation report values
        $reportTypes = $request->input('report_types', []);
        $values = $request->input('values', []);
        $outOfRange = $request->input('out_of_range', []);

        foreach ($reportTypes as $index => $reportTypeId) {
            InvestigationReportValues::create([
                'investigation_report_id' => $investigationReport->id,
                'investigation_report_type_id' => $reportTypeId,
                'value' => $values[$index] ?? null,
                'out_of_range' => isset($outOfRange[$index]) ? 1 : 0, // Set to 'yes' if checkbox is checked, otherwise 'no'
            ]);
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Investigation Report uploaded successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(InvestigationReport $investigationReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigationReport $investigationReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvestigationReport $investigationReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestigationReport $investigationReport)
    {
        //
    }
}
