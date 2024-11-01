<?php

namespace App\Http\Controllers;

use App\DataTables\BrandDataTable;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BrandDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('brand.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('brand.create');
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
        $brand = new Brand();
        $brand->name = $request->input('name');
        $brand->save();

        return redirect()->route('brand.index')->with('success', 'Brand Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('brand.update', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        // dd($category);
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update the category details
        $brand->name = $request->input('name');
        $brand->save();

        // Redirect to the category index page with a success message
        return redirect()->route('brand.index')->with('success', 'Brand Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        // Delete the category
        $brand->delete();

        // Redirect to the category index page with a success message
        return redirect()->route('brand.index')->with('success', 'Brand Deleted Successfully');
    }
}
