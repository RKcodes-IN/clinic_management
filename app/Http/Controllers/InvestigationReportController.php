<?php

namespace App\Http\Controllers;

use App\Models\InvestigationReport;
use App\Models\InvestigationReportType;
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
        // Validate the request data
        // $request->validate([
        //     'patient_id' => 'required',
        //     'report_type_id' => 'required',
        //     'report_url' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Adjust file types and size as needed
        // ]);

        // Handle file upload
        $filePath = $request->file('report_url')->store('investigation_reports', 'public');
        // Save the investigation report to the database
        $investigationReport = new InvestigationReport();
        $investigationReport->patient_id = $request->input('patient_id');
        $investigationReport->report_type_id = $request->input('report_type_id');
        $investigationReport->report_url = $filePath;
        $investigationReport->report_date = $request->report_date;
        $investigationReport->tsh = $request->tsh;
        $investigationReport->traferrin_sat = $request->traferrin_sat;
        $investigationReport->vitamin_b = $request->vitamin_b;
        $investigationReport->sodium = $request->sodium;
        $investigationReport->save();

        // Redirect back to the form with a success message
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
