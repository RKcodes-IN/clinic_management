<?php

namespace App\Http\Controllers;

use App\DataTables\UomTypeDataTable;
use App\Models\UomType;
use Illuminate\Http\Request;

class UomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UomTypeDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('uomtype.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('uomtype.create');
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
        $doctorDetail = new UomType();
        $doctorDetail->name = $request->input('name');
        $doctorDetail->save();

        return redirect()->route('uomtype.index')->with('success', 'UOM Type Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(UomType $uomType) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UomType $uomType)
    {
        return view('uomtype.update', compact('uomType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UomType $uomType)
    {
        // dd($category);
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update the category details
        $uomType->name = $request->input('name');
        $uomType->save();

        // Redirect to the category index page with a success message
        return redirect()->route('uomtype.index')->with('success', 'Uom Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UomType $uomType)
    {
        // Delete the category
        $uomType->delete();

        // Redirect to the category index page with a success message
        return redirect()->route('uomtype.index')->with('success', 'Uom Deleted Successfully');
    }
}
