<?php

namespace App\Http\Controllers;

use App\DataTables\PaitentDetailDataTable;
use App\Models\Appointment;
use App\Models\HealthEvaluation;
use App\Models\patient_detail;
use App\Models\PatientDetail;
use Illuminate\Http\Request;

class PatientDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaitentDetailDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('paitentdetail.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paitent = PatientDetail::findOrFail($id);

        $healthEvalutions = HealthEvaluation::where('patient_id', $paitent->id)->get();

        $appontments = Appointment::with(['doctor'])->where('patient_id', $id)->get();

        return view('paitentdetail.show', compact('paitent', 'healthEvalutions', 'appontments'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, patient_detail $patient_detail)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(patient_detail $patient_detail)
    // {
    //     //
    // }
}
