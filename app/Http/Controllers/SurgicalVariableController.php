<?php

namespace App\Http\Controllers;

use App\DataTables\SurgicalVariableDataTable;
use App\Models\SurgicalVariable;
use Illuminate\Http\Request;

class SurgicalVariableController extends Controller
{
    public function index(SurgicalVariableDataTable $dataTable)
    {
        return $dataTable->render('surgical_variables.index');
    }

    public function create()
    {
        return view('surgical_variables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:1,2',
        ]);

        SurgicalVariable::create($request->all());
        return redirect()->route('surgical-variables.index')->with('success', 'Surgical Variable created successfully.');
    }

    public function show(SurgicalVariable $surgicalVariable)
    {
        return view('surgical_variables.show', compact('surgicalVariable'));
    }

    public function edit(SurgicalVariable $surgicalVariable)
    {
        return view('surgical_variables.edit', compact('surgicalVariable'));
    }

    public function update(Request $request, SurgicalVariable $surgicalVariable)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:1,2',
        ]);

        $surgicalVariable->update($request->all());
        return redirect()->route('surgical-variables.index')->with('success', 'Surgical Variable updated successfully.');
    }

    public function destroy(SurgicalVariable $surgicalVariable)
    {
        $surgicalVariable->delete();
        return redirect()->route('surgical-variables.index')->with('success', 'Surgical Variable deleted successfully.');
    }
}
