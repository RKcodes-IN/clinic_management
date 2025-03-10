<?php

namespace App\Http\Controllers;

use App\Imports\InvoiceDetailImport;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function importForm(Request $request)
    {
        return view('invoice_detail.import');
    }

    public function import(Request $request)
    {

        ini_set('max_execution_time', 300);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv', // Validate file type
        ]);

        // Import the Excel file
        Excel::import(new InvoiceDetailImport, $request->file('file'));

        return redirect()->back()->with('success', 'Import Form');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(InvoiceDetail $invoiceDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceDetail $invoiceDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceDetail $invoiceDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceDetail $invoiceDetail)
    {
        //
    }
}
