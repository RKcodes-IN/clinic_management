<?php

namespace App\Http\Controllers;

use App\DataTables\ItemDataTable;
use App\DataTables\StockReportDataTable;
use App\Imports\ItemsImport;
use App\Imports\RackUpdatation;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\SourceCompany;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\UomType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ItemDataTable $dataTable)
    {
        // Get 'item_type' parameter dynamically from the request
        $itemType = request()->get('item_type');
        // Pass 'item_type' to the DataTable
        return $dataTable->with('item_type', $itemType)->render('items.index');
    }



    public function stockReport(StockReportDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('items.stockreport');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type');
        $uomTypes = UomType::all();
        $brands = Brand::all();
        $companies = SourceCompany::all();
        $categories = Category::all();

        return view('items.create', compact('type', 'uomTypes', 'brands', 'companies', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|unique:items,item_code',
            'name' => 'required|string',
            'brand' => 'required|exists:brands,id',
            'source_company' => 'required|exists:source_companies,id',
        ]);
        // Merge status = 1 into request data
        $data = $request->all();
        $data['status'] = 1;
        $data['category_id'] = $request->category_id;
        $data['brand_id'] = $request->brand;

        // Create the item
        Item::create($data);

        return redirect()->back()->with('success', 'Item created successfully.');
    }


    public function importRackForm(Request $request)
    {
        return view('items.rackimport');
    }
    public function importRack(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new RackUpdatation, $request->file('file'));
        return redirect()->back()->with('success', 'Rack information updated successfully');
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

    public function stockReportView(Item $item)
    {
        // `$item` already contains the Item instance passed via route model binding
        $stock = Stock::where('item_id', $item->id)->get(); // Retrieve stock related to the item
        $stockTransaction = StockTransaction::where('item_id', $item->id)->get(); // Retrieve stock transactions

        return view('items.stockreportview', compact('item', 'stock', 'stockTransaction'));
    }
    public function stockReportPDF(Item $item)
    { // Retrieve stock and stock transactions data
        $stock = Stock::where('item_id', $item->id)->get();
        $stockTransaction = StockTransaction::where('item_id', $item->id)->get();

        // Render the Blade view to HTML (for mPDF rendering)
        $pdfContent = view('items.stockreportpdf', compact('item', 'stock', 'stockTransaction'))->render();

        // Initialize mPDF
        $mpdf = new Mpdf();

        // Write HTML to PDF
        $mpdf->WriteHTML($pdfContent);

        // Return the generated PDF for download
        return $mpdf->Output('Stock_Report_' . $item->name . '.pdf', 'D');
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $uomTypes = UomType::all();
        $brands = Brand::all();
        $companies = SourceCompany::all();

        return view('items.update', compact('item', 'uomTypes', 'brands', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'item_code' => "required|unique:items,item_code,{$id}|string|max:255",
            'name' => 'required|string|max:255',
            'item_type' => 'required|in:' . implode(',', array_keys(Item::getItemTypes())),
            'uom_type' => 'required|exists:uom_types,id',
            'brand' => 'required|exists:brands,id',
            'source_company' => 'required|exists:source_companies,id',
            'alert_quantity' => 'required|numeric|min:0',
            'ideal_quantity' => 'required|numeric|min:0',
            'reorder_quantity' => 'required|numeric|min:0',
            'max_discount_percentage' => 'nullable|numeric|min:0|max:100', // New validation rule
        ]);

        $item->update($request->all());

        return redirect()->route('items.edit', $item->id)->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Item $item)
    // {
    //     //
    // }
}
