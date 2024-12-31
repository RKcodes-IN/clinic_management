<?php

namespace App\Http\Controllers;

use App\DataTables\SourceCompanyDataTable;
use App\Models\SourceCompany;
use Illuminate\Http\Request;

class SourceCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SourceCompanyDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('source-company.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('source-company.create');
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
        $doctorDetail = new SourceCompany();
        $doctorDetail->name = $request->input('name');
        $doctorDetail->save();

        return redirect()->route('source-company.index')->with('success', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(SourceCompany $sourceCompany) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $sourceCompany = SourceCompany::where('id', $id)->first();

        return view('source-company.update', compact('sourceCompany'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email|max:100',
            'phone_one' => 'required|string|max:50',
            'phone_two' => 'nullable|string|max:50',
            'gst_no' => 'required|string|max:30',
            'contact_person' => 'required|string|max:50',
        ]);

        $sourceCompany = SourceCompany::findOrFail($id);
        $sourceCompany->name = $request->input('name');
        $sourceCompany->address = $request->input('address');
        $sourceCompany->email = $request->input('email');
        $sourceCompany->phone_one = $request->input('phone_one');
        $sourceCompany->phone_two = $request->input('phone_two');
        $sourceCompany->gst_no = $request->input('gst_no');
        $sourceCompany->contact_person = $request->input('contact_person');
        $sourceCompany->save();

        return redirect()->route('source-company.index')->with('success', 'Source Company Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Delete the category
        $sourceCompany = SourceCompany::where('id', $id)->first();

        $sourceCompany->delete();

        // Redirect to the category index page with a success message
        return redirect()->route('source-company.index')->with('success', 'Source Company Deleted Successfully');
    }
}
