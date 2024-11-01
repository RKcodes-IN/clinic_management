<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\GstType;
use App\Models\SourceCompany;
use App\Models\Stock;
use App\Models\UomType;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sourceCompanies = SourceCompany::all();
        $uomTypes = UomType::all();
        $brands = Brand::all();
        $categories = Category::all();
        $gstTypes = GstType::all();

        return view('stock.create', compact('sourceCompanies', 'uomTypes', 'brands', 'categories', 'gstTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'item_code_name' => 'required|string|max:255',
            'invoice_number' => 'required|string|max:255',
            'purchase_invoice_date' => 'required|date',
            'source_name' => 'required|exists:source_companies,id', // Ensure it exists in the SourceCompany table
            // 'brand' => 'required|exists:brands,id', // Ensure it exists in the Brand table
            'category' => 'required|exists:categories,id', // Ensure it exists in the Category table
            'batch' => 'required|string|max:255',
            'expiry_date' => 'required|date',
            'hsn_code' => 'required|string|max:255',
            'uom_type' => 'required|exists:uom_types,id', // Ensure it exists in the UomType table
            'mrp' => 'required|numeric',
            'discount_percentage' => 'required|numeric',
            'discount_price' => 'required|numeric',
            'additional_discount_percentage' => 'required|numeric',
            'additional_discount_price' => 'required|numeric',
            // 'gst_type' => 'required|exists:gst_types,id', // Ensure it exists in the GstType table
            'gst_amount' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'courier_price_percentage' => 'required|numeric',
            'courier_charge_amount' => 'required|numeric',
            'final_cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'sale_discount' => 'required|numeric',
            'profit_margin' => 'required|numeric',
            'purchase_quantity' => 'required|numeric',
            'status' => 'required|in:active,inactive,delete', // Validate the status options
        ]);

        // Create a new Stock record
        Stock::create([
            'item_code_name' => $request->item_code_name,
            'invoice_number' => $request->invoice_number,
            'purchase_invoice_date' => $request->purchase_invoice_date,
            'source_name' => $request->source_name,
            'brand' => $request->brand,
            'category' => $request->category,
            'batch' => $request->batch,
            'expiry_date' => $request->expiry_date,
            'hsn_code' => $request->hsn_code,
            'uom_type' => $request->uom_type,
            'mrp' => $request->mrp,
            'discount_percentage' => $request->discount_percentage,
            'discount_price' => $request->discount_price,
            'additional_discount_percentage' => $request->additional_discount_percentage,
            'additional_discount_price' => $request->additional_discount_price,
            'gst_type' => $request->gst_type,
            'gst_amount' => $request->gst_amount,
            'cost_price' => $request->cost_price,
            'courier_price_percentage' => $request->courier_price_percentage,
            'courier_charge_amount' => $request->courier_charge_amount,
            'final_cost_price' => $request->final_cost_price,
            'sale_price' => $request->sale_price,
            'sale_discount' => $request->sale_discount,
            'profit_margin' => $request->profit_margin,
            'purchase_quantity' => $request->purchase_quantity,
            'status' => $request->status,
        ]);

        // Redirect back with a success message
        return redirect()->route('stock.create')->with('success', 'Stock created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
