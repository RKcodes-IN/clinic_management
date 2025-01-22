<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('category.create');
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
        $doctorDetail = new Category();
        $doctorDetail->name = $request->input('name');
        $doctorDetail->save();

        return redirect()->route('category.index')->with('success', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('category.update', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // dd($category);
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update the category details
        $category->name = $request->input('name');
        $category->status = $request->input('status');

        $category->save();

        // Redirect to the category index page with a success message
        return redirect()->route('category.index')->with('success', 'Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Delete the category
        $category->delete();

        // Redirect to the category index page with a success message
        return redirect()->route('category.index')->with('success', 'Category Deleted Successfully');
    }
}
