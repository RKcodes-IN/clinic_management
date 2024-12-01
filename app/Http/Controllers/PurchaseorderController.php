<?php

namespace App\Http\Controllers;

use App\DataTables\purchaseOrderDataTable;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SourceCompany;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(purchaseOrderDataTable $dataTable)
    {
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

        $items = Item::where('source_company', $request->source_company_id)->get(); // Fetch items for selected source company
        return view('purchase_order.create', compact('items', 'sourceCompanies'));
    }


    public function createOrder(Request $request)
    {
        if (empty($request->items)) {
            return redirect()->back()->with('error', 'Please select at least 1 item');
        }

        $purchaseorder = new Purchaseorder();
        $purchaseorder->source_company_id = $request->source_company_id;
        $purchaseorder->total_item = count($request->items);
        $purchaseorder->total_quantity = 0;
        $purchaseorder->price = 0;
        $purchaseorder->status =  PurchaseOrderItem::STATUS_PENDING;
        $purchaseorder->creation_date = date('Y-m-d');

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

            // Loop through the received items
            foreach ($requestData as $data) {
                // Validate the data for each item
                $validator = Validator::make($data, [
                    'item_id' => 'required|integer|exists:purchaseorder_items,id',
                    'received_quantity' => 'required|numeric|min:1',
                    'unit_price' => 'required|numeric|min:0',
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
                $purchaseOrderItem = PurchaseOrderItem::find($data['item_id']);

                if ($purchaseOrderItem) {
                    // Update the item's details
                    $purchaseOrderItem->update([
                        'received_quantity' => $data['received_quantity'],
                        'unit_price' => $data['unit_price'],
                        'total_price' => $data['total_price'],
                        'expiry_date' => $data['expiry_date'],
                        'received_date' => $data['received_date']
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "Purchase order item with ID {$data['item_id']} not found."
                    ], 404);
                }
            }

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
