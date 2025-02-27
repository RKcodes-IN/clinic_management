<?php

namespace App\Http\Controllers;

use App\DataTables\purchaseOrderDataTable;
use App\Imports\PurchaseOrderImport;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SourceCompany;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(purchaseOrderDataTable $dataTable)
    {

        // Cast the view to a type Laravel expects
        return $dataTable->render('purchase_order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function createPurchaseOrderForm()
    {
        $sourceCompanies = SourceCompany::all(); // Fetch all source companies
        return view('purchase_order.create', compact('sourceCompanies'));
    }

    public function fetchItems(Request $request)
    {
        $sourceCompanies = SourceCompany::all(); // Fetch all source companies

        $items = DB::table('items')
            ->where('source_company', $request->source_company_id)
            ->where('item_type', Item::TYPE_PHARMACY)
            ->orderBy('name', 'asc')
            ->get();
        return view('purchase_order.create', compact('items', 'sourceCompanies'));
    }


    public function createOrder(Request $request)
    {
        if (empty($request->items)) {
            return redirect()->back()->with('error', 'Please select at least 1 item');
        }
        // dd($request);

        $purchaseorder = new Purchaseorder();
        $purchaseorder->source_company_id = $request->source_company_id;
        $purchaseorder->total_item = count($request->items);
        $purchaseorder->total_quantity = 0;
        $purchaseorder->price = 0;
        $purchaseorder->status =  PurchaseOrderItem::STATUS_PENDING;
        $purchaseorder->creation_date = $request->date;

        if ($purchaseorder->save()) {
            foreach ($request->items as $itemId) {
                $item = Item::find($itemId);
                if ($item) {
                    $purchaseOrderItem = new PurchaseOrderItem();
                    $purchaseOrderItem->purchase_order_id = $purchaseorder->id;
                    $purchaseOrderItem->source_company_id = $request->source_company_id;
                    $purchaseOrderItem->item_id = $item->id;
                    $purchaseOrderItem->uom_type_id = 0;
                    $purchaseOrderItem->item_price = 0;
                    $purchaseOrderItem->total_price = 0;
                    $purchaseOrderItem->order_date = date('Y-m-d');
                    $purchaseOrderItem->status = PurchaseOrderItem::STATUS_PENDING;
                    $purchaseOrderItem->save();
                }
            }

            return redirect()->route('editPurchaseOrderItems', ['purchase_order_id' => $purchaseorder->id]);
        }

        return redirect()->back()->with('error', 'Failed to create purchase order');
    }

    public function editPurchaseOrderItems($purchase_order_id)
    {
        $purchaseOrder = PurchaseOrder::with('purchaseOrderItems')->findOrFail($purchase_order_id);
        return view('purchase_order.edit_items', compact('purchaseOrder'));
    }

    public function downloadPdf($id)
    {
        $purchaseOrder = PurchaseOrder::with(['purchaseOrderItems.item', 'purchaseOrderItems.item.uom'])->findOrFail($id);
        $company = SourceCompany::findOrFail($purchaseOrder->source_company_id); // Assuming you have company details linked

        $html = view('purchase_order/order_pdf', compact('purchaseOrder', 'company'))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        $fileName = 'Purchase_Order_' . $purchaseOrder->id . '.pdf';

        return $mpdf->Output(name: $fileName, dest: 'D'); // Download the PDF
    }
    public function updatePurchaseOrderItems(Request $request, $purchase_order_id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchase_order_id);

        foreach ($request->items as $itemId => $quantity) {
            $purchaseOrderItem = PurchaseOrderItem::where('purchase_order_id', $purchase_order_id)
                ->where('item_id', $itemId)
                ->first();

            if ($purchaseOrderItem) {
                $purchaseOrderItem->quantity = $quantity;
                $purchaseOrderItem->save();
            }
        }

        $purchaseOrder->status = PurchaseOrderItem::STATUS_CREATED;
        $purchaseOrder->save();
        return redirect()->route('purchaseorder.index')
            ->with('success', 'Items updated successfully.');
    }

    public function receive(Request $request)
    {
        if (!empty($request->all())) {
            $requestData = $request->all();
            foreach ($requestData as $data) {
                // Validate the data for each item
                $validator = Validator::make($data, [
                    'item_id' => 'required|integer',
                    'received_quantity' => 'required|numeric|min:1',
                    'unit_price' => 'required|numeric|min:0',
                    'purchase_price' => 'required|numeric|min:0',
                    'discount_amount' => 'nullable|numeric|min:0',
                    'additional_discount_amount' => 'nullable|numeric|min:0',
                    'taxable_amount' => 'required|numeric|min:0',
                    'gst_percentage' => 'required|numeric|min:0',
                    'gst_amount' => 'required|numeric|min:0',
                    'total_price' => 'required|numeric|min:0',
                    'expiry_date' => 'required|date',
                    'received_date' => 'required|date'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation error',
                        'errors' => $validator->errors()
                    ], 422);
                }

                // Fetch the item from the database
                $purchaseOrderItem = PurchaseOrderItem::find($data['purchase_order_item_id']);

                // if (!$purchaseOrderItem) {
                //     return response()->json([
                //         'success' => false,
                //         'message' => "Purchase order item with ID {$data['item_id']} not found."
                //     ], 404);
                // }

                // Determine status of the current item
                if ($purchaseOrderItem->quantity == ($purchaseOrderItem->received_quantity + $data["received_quantity"])) {
                    $status = PurchaseOrderItem::STATUS_RECIEVED;
                } else {
                    $status = PurchaseOrderItem::STATUS_PARTIAL_RECIEVED;
                }

                // Update the item's details
                $purchaseOrderItem->update([
                    'received_quantity'           => $purchaseOrderItem->received_quantity + $data['received_quantity'],
                    'item_price'                  => $data['unit_price'],
                    'gst_percentage'              => $data['gst_percentage'],
                    'gst_amount'                  => $data['gst_amount'],
                    'purchase_price'              => $data['purchase_price'],
                    'item_price'                  => $data['unit_price'],
                    'discount_amount'             => $data['discount_amount'] ?? 0,
                    'additional_discount_amount'  => $data['additional_discount_amount'] ?? 0,
                    'taxable_amount'              => $data['taxable_amount'],
                    'total_price'                 => $data['total_price'],
                    'expiry_date'                 => $data['expiry_date'],
                    'received_date'               => $data['received_date'],
                    'status'                      => $status
                ]);
                // Save stock data
                $stock = new Stock();
                $stock->purchase_order_id = $purchaseOrderItem->purchase_order_id;
                $stock->purchase_order_item_id = $purchaseOrderItem->id;
                $stock->item_id = $data['item_id'];
                $stock->order_quantity = $data['received_quantity'];
                $stock->item_price = $data['unit_price'];
                $stock->batch_no = $data['batch'];
                $stock->total_price = $purchaseOrderItem->total_price;
                $stock->order_date = $purchaseOrderItem->created_on;
                $stock->received_date = $purchaseOrderItem->received_date;
                $stock->expiry_date = $data['expiry_date'];
                $stock->status = 1;

                if ($stock->save()) {
                    // Save stock transaction
                    $stockTransaction = new StockTransaction();
                    $stockTransaction->stock_id = $stock->id;
                    $stockTransaction->item_id = $stock->item_id;
                    $stockTransaction->invoice_id = 0;
                    $stockTransaction->purchase_order_id = $purchaseOrderItem->purchase_order_id;
                    $stockTransaction->quantity = $stock->order_quantity * ($stock->item->unit_conversion_ratio ?: 1);
                    $stockTransaction->item_price = $stock->item_price;
                    $stockTransaction->status = StockTransaction::STATUS_INCOMING_STOCK;
                    $stockTransaction->transaction_date = date('Y-m-d');
                    $stockTransaction->save();
                }
            }

            // Update the purchase order status
            $purchaseOrderId = $purchaseOrderItem->purchase_order_id;
            $purchaseOrderItems = PurchaseOrderItem::where('purchase_order_id', $purchaseOrderId)->get();

            if ($purchaseOrderItems->every(fn($item) => $item->status == PurchaseOrderItem::STATUS_RECIEVED)) {
                // All items are received
                $purchaseOrderStatus = PurchaseOrder::STATUS_RECIEVED;
            } else {
                // Some items are partially received
                $purchaseOrderStatus = PurchaseOrder::STATUS_PARTIAL_RECIEVED;
            }

            PurchaseOrder::where('id', $purchaseOrderId)->update(['status' => $purchaseOrderStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Items successfully updated as received.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No data received.'
            ], 400);
        }
    }


    public function importForm()
    {
        return view('purchase_order.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new PurchaseOrderImport, $request->file('file'));

        return redirect()->back()->with('success', 'Purchase Order Imported!');
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
    public function show($id)
    {
        // Fetch the purchase order along with its related items
        $purchaseOrder = PurchaseOrder::with('purchaseOrderItems')->findOrFail($id);

        return view('purchase_order.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
