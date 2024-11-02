<?php

namespace App\Http\Controllers;

use App\DataTables\InvestigationReportTypeDataTable;
use App\Models\InvestigationReport;
use App\Models\InvestigationReportType;
use Illuminate\Http\Request;

class InvestigationReportTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InvestigationReportTypeDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('investigationreporttype.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('investigationreporttype.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);




        // Create DoctorDetail
        $doctorDetail = new InvestigationReportType();
        $doctorDetail->name = $request->input('name');
        $doctorDetail->save();

        return redirect()->route('investigationreporttype.index')->with('success', 'Investigation Report Type Created Successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(InvestigationReportType $investigationReportType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigationReportType $investigationReportType)
    {
        return view('investigationreporttype.update', compact('investigationReportType'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvestigationReport $investigationReportType)
    {

        dd($investigationReportType);
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update the category details
        $investigationReportType->name = $request->input('name');
        $investigationReportType->save();

        // Redirect to the category index page with a success message
        return redirect()->route('investigationreporttype.index')->with('success', 'investigationreporttype Updated Successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestigationReportType $investigationReportType)
    {
        //
    }
}
