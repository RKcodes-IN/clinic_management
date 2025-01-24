<?php

namespace App\Http\Controllers;

use App\DataTables\SampleTypeDataTable;
use App\Models\SampleType;
use Illuminate\Http\Request;

class SampleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SampleTypeDataTable $dataTable)
    {
        return $dataTable->render('sample_types.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sample_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        SampleType::create($validated);

        return redirect()->route('sample-types.index')
            ->with('success', 'Sample type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleType $sampleType)
    {
        return view('sample_types.show', compact('sampleType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampleType $sampleType)
    {
        return view('sample_types.edit', compact('sampleType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampleType $sampleType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $sampleType->update($validated);

        return redirect()->route('sample-types.index')
            ->with('success', 'Sample type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleType $sampleType)
    {
        $sampleType->delete();

        return redirect()->route('sample-types.index')
            ->with('success', 'Sample type deleted successfully');
    }
}
