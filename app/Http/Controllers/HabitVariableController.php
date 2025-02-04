<?php

namespace App\Http\Controllers;

use App\DataTables\HabitVariableDataTable;
use App\Models\HabitVariable;
use Illuminate\Http\Request;

class HabitVariableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(HabitVariableDataTable $dataTable)
    {
        return $dataTable->render('habit_variables.index');
    }

    public function create()
    {
        $statusOptions = [
            HabitVariable::STATUS_ACTIVE => 'Active',
            HabitVariable::STATUS_INACTIVE => 'Inactive',
        ];
        return view('habit_variables.create', compact('statusOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:1,2',
        ]);

        HabitVariable::create($validated);

        return redirect()->route('habit-variables.index')
            ->with('success', 'Habit variable created successfully.');
    }

    public function show(HabitVariable $habitVariable)
    {
        return view('habit_variables.show', compact('habitVariable'));
    }

    public function edit(HabitVariable $habitVariable)
    {
        $statusOptions = [
            HabitVariable::STATUS_ACTIVE => 'Active',
            HabitVariable::STATUS_INACTIVE => 'Inactive',
        ];
        return view('habit_variables.update', compact('habitVariable', 'statusOptions'));
    }

    public function update(Request $request, HabitVariable $habitVariable)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:1,2',
        ]);

        $habitVariable->update($validated);

        return redirect()->route('habit-variables.index')
            ->with('success', 'Habit variable updated successfully.');
    }

    public function destroy(HabitVariable $habitVariable)
    {
        $habitVariable->delete();

        return redirect()->route('habit-variables.index')
            ->with('success', 'Habit variable deleted successfully.');
    }
}
