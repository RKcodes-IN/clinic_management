<?php

namespace App\Http\Controllers;

use App\DataTables\ItemDataTable;
use App\Imports\ItemsImport;
use App\Models\Brand;
use App\Models\Item;
use App\Models\SourceCompany;
use App\Models\UomType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ItemDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('items.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $uomTypes = UomType::all();
        $brands = Brand::all();
        $companies = SourceCompany::all();

        return view('items.create', compact('uomTypes', 'brands', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|unique:items,item_code',
            'name' => 'required|string',
            'uom_type' => 'required|exists:uom_types,id',
            'brand' => 'required|exists:brands,id',
            'source_company' => 'required|exists:source_companies,id',
        ]);

        Item::create($request->all());

        return redirect()->route('items.create')->with('success', 'Item created successfully.');
    }

    public function exportExcel(ItemDataTable $dataTable)
    {
        return $dataTable->excel();
    }

    public function importForm(Request $request)
    {
        return view('items.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv', // Validate file type
        ]);

        // Import the Excel file
        Excel::import(new ItemsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Items imported successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
