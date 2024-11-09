<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchaseorder;
use Illuminate\Http\Request;

class PurchaseorderController extends Controller
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
        // Fetch items using the Item model
        $items = Item::select('id', 'name', 'item_code')->get();

        return view('purchaseorders.create', compact('items'));
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
    public function show(Purchaseorder $purchaseorder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchaseorder $purchaseorder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchaseorder $purchaseorder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchaseorder $purchaseorder)
    {
        //
    }
}
