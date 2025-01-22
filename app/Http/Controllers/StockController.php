<?php

namespace App\Http\Controllers;

use App\DataTables\StockDataTable;
use App\Exports\StockExport;
use App\Exports\StockTransactionsExport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\GstType;
use App\Models\Item;
use App\Models\SourceCompany;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\UomType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StockDataTable $dataTable)
    {
        // Get the 'item_type' parameter from the query string
        $itemType = request()->get('item_type');

        // Pass 'item_type' to the DataTable
        return $dataTable->with('item_type', $itemType)->render('stock.index');
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


    public function stockReportFilterView()
    {
        $items = Item::where('item_type', 1)
            ->whereNotNull('name') // Exclude items with null names
            ->where('name', '!=', '') // Exclude items with empty names
            ->where('status', '=', Item::TYPE_PHARMACY) // Exclude items with empty names
            ->get(); // Fetch all items to populate the dropdown
        return view('stock.stockreport', compact('items'));
    }

    public function filterReport(Request $request)
    {
        // Validate the request
        $request->validate([
            'item' => 'nullable|array',
            'item.*' => 'exists:items,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $itemIds = $request->input('item', []);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Build the query
        $query = Stock::query();

        // Apply filters
        if (!empty($itemIds) && !in_array('all', $itemIds)) {
            $query->whereIn('item_id', $itemIds);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }

        // Fetch filtered transactions with relationships
        $transactions = $query->with(['item'])->orderBy('id', 'asc')->get();

        // Fetch all items for the dropdown
        $items = Item::all();

        // Return view with data
        return view('stock.stockreport', compact('transactions', 'items', 'itemIds', 'fromDate', 'toDate'));
    }


    public function exportReport(Request $request)
    {
        // Decode the JSON-encoded `item` input or set to an empty array if null
        $itemIds = $request->input('item', '[]');
        $itemIds = is_string($itemIds) ? json_decode($itemIds, true) : $itemIds;

        // Ensure $itemIds is an array
        if (!is_array($itemIds)) {
            $itemIds = [];
        }

        // dd($itemIds);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Build the query
        $query = Stock::query();

        // Apply filters
        if (!empty($itemIds) && !in_array('all', $itemIds)) {
            $query->whereIn('item_id', $itemIds);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }

        // Fetch filtered transactions
        $transactions = $query->with(['item'])->orderBy('id', 'asc')->get();

        // Use Maatwebsite Excel to create and download the export
        return Excel::download(new StockExport($transactions), 'stock_transactions.xlsx');
    }


    public function exportPdfReport(Request $request)
    {
        // Fetch the filter values
        $itemIds = $request->input('item', []);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Validate the filter values (same as for the export)
        if (!is_array($itemIds)) {
            $itemIds = json_decode($itemIds, true) ?? [];
        }

        // Query the filtered transactions
        $query = Stock::query();

        if (!empty($itemIds) && !in_array('all', $itemIds)) {
            $query->whereIn('item_id', $itemIds);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }

        // Fetch the filtered transactions with the related data
        $transactions = $query->with('item')->orderBy('id', 'asc')->get();

        // Generate the HTML content for the PDF
        $html = view('stock.pdf.stock_report_export', compact('transactions', 'fromDate', 'toDate'))->render();

        // Initialize mPDF
        $mpdf = new Mpdf();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF (download the file)
        return $mpdf->Output('stock_report_export.pdf', 'D');
    }


    public function getAvailableStock($stockId)
    {
        $stock = Stock::find($stockId);

        if (!$stock) {
            return response()->json(['available_stock' => 0], 404);
        }

        $availableStock = $stock->getTotalStock($stockId);

        return response()->json(['available_stock' => $availableStock]);
    }

    public function getDisount($itemId)
    {
        $item = Item::find($itemId);

        if (!$item) {
            return response()->json(['discount' => 0]);
        }
        if (empty($item->max_discount_percentage)) {
            return response()->json(['discount' => 0]);
        }


        return response()->json(['discount' => $item->max_discount_percentage]);
    }


    // Report


    public function stockFilterView()
    {
        $items = Item::where('status', '=', Item::TYPE_PHARMACY)-> // Exclude items with empty names

            get(); // Fetch all items to populate the dropdown
        return view('stock.stocktransaction', compact('items'));
    }

    public function filter(Request $request)
    {
        // Validate the request
        $request->validate([
            'item' => 'nullable|array',
            'item.*' => 'exists:items,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $itemIds = $request->input('item', []);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Build the query
        $query = StockTransaction::query();

        // Apply filters
        if (!empty($itemIds) && !in_array('all', $itemIds)) {
            $query->whereIn('item_id', $itemIds);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }

        // Fetch filtered transactions with relationships
        $transactions = $query->with(['item', 'invoiceDetail'])->orderBy('id', 'asc')->get();

        // Fetch all items for the dropdown
        $items = Item::all();

        // Return view with data
        return view('stock.stocktransaction', compact('transactions', 'items', 'itemIds', 'fromDate', 'toDate'));
    }


    public function export(Request $request)
    {
        // Decode the JSON-encoded `item` input or set to an empty array if null
        $itemIds = $request->input('item', '[]');
        $itemIds = is_string($itemIds) ? json_decode($itemIds, true) : $itemIds;

        // Ensure $itemIds is an array
        if (!is_array($itemIds)) {
            $itemIds = [];
        }

        // dd($itemIds);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Build the query
        $query = StockTransaction::query();

        // Apply filters
        if (!empty($itemIds) && !in_array('all', $itemIds)) {
            $query->whereIn('item_id', $itemIds);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }

        // Fetch filtered transactions
        $transactions = $query->with(['item', 'invoiceDetail'])->orderBy('id', 'asc')->get();

        // Use Maatwebsite Excel to create and download the export
        return Excel::download(new StockTransactionsExport($transactions), 'stock_transactions.xlsx');
    }


    public function exportPdf(Request $request)
    {
        // Fetch the filter values
        $itemIds = $request->input('item', []);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Validate the filter values (same as for the export)
        if (!is_array($itemIds)) {
            $itemIds = json_decode($itemIds, true) ?? [];
        }

        // Query the filtered transactions
        $query = StockTransaction::query();

        if (!empty($itemIds) && !in_array('all', $itemIds)) {
            $query->whereIn('item_id', $itemIds);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }

        // Fetch the filtered transactions with the related data
        $transactions = $query->with('item')->orderBy('id', 'asc')->get();

        // Generate the HTML content for the PDF
        $html = view('stock.pdf.stock_transactions', compact('transactions', 'fromDate', 'toDate'))->render();

        // Initialize mPDF
        $mpdf = new Mpdf();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF (download the file)
        return $mpdf->Output('stock_transactions.pdf', 'D');
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
            'category' => $request->category_id,
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

    public function updatePricing($type)
    {
        if ($type === "mis") {
            $items = Item::where('item_type', Item::MISCELLANEOUS)->get();
        } else {
            $items = Item::where('item_type', Item::TYPE_LAB)->get();
        }
        return view('stock.update-pricing', compact('items'));
    }


    public function updatePrice(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id', // Ensure the item_id exists in the items table
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
        ]);

        try {
            $userId = Auth::id();
            $currentDate = Carbon::today();

            // Fetch the latest stock entry for the item
            $stock = Stock::where('item_id', $request->item_id)
                ->orderBy('start_date', 'DESC')
                ->first();

            if (!$stock) {
                return redirect()->back()->withErrors(['error' => 'No existing stock found for the specified item.']);
            }

            // Add new stock entry
            $addNewStock = new Stock();
            $addNewStock->purchase_order_id = 0;
            $addNewStock->purchase_order_item_id = 0;
            $addNewStock->item_id = $request->item_id;
            $addNewStock->item_price = $request->price;
            $addNewStock->total_price = $request->price; // Assuming total_price is same as price for now
            $addNewStock->order_date = $currentDate;
            $addNewStock->received_date = $currentDate;
            $addNewStock->expiry_date = null; // Adjust if needed
            $addNewStock->batch_no = "";
            $addNewStock->start_date = $request->start_date;
            $addNewStock->status = Stock::IN_STOCK;
            $addNewStock->created_by = $userId;
            $addNewStock->save();
            $stock->status = Stock::EXPIRED;
            $stock->save();
            return redirect()->back()->with('success', 'Price Updated Successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['error' => 'Stock not found for the specified item.']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
