<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Item;
use App\Models\ReturnRequest;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnRequestController extends Controller
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
        return view('return-request.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Generate unique return code
            $returnCode = $this->generateUniqueReturnCode();

            // Create return request
            $returnRequest = ReturnRequest::create([
                'invoice_id' => $request->invoice_id,
                'patient_id' => $request->patient_id,
                'return_code' => $returnCode,
                'return_date' => $request->return_date,
                'return_status' => $request->return_status,
                'total_amount' => $request->total_amount,
                // 'created_by' => auth()->id()
            ]);

            // Process return items
            $items = json_decode($request->items, true);
            foreach ($items as $item) {
                $returnRequest->items()->create([
                    'item_id' => $item['item_id'],
                    'invoice_detail_id' => $item['invoice_detail_id'],
                    'return_quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'return_amount' => $item['total_amount'],
                    'return_reason' => $item['reason']
                ]);

                // Update stock if needed
                if ($request->return_status === 'approved') {
                    $this->updateStock($item['item_id'], $item['quantity']);
                }
            }

            DB::commit();
            return redirect()->route('return-request.index')->with('success', 'Return request created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating return request: ' . $e->getMessage());
        }
    }

    private function generateUniqueReturnCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (ReturnRequest::where('return_code', $code)->exists());

        return $code;
    }

    private function updateStock($itemId, $quantity)
    {
        // Find the latest stock entry for this item
        $stock = Stock::where('item_id', $itemId)
            ->where('status', Stock::IN_STOCK)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($stock) {
            // Create a new stock transaction for the return
            StockTransaction::create([
                'stock_id' => $stock->id,
                'item_id' => $itemId,
                'quantity' => $quantity,
                'status' => StockTransaction::STATUS_INCOMING_STOCK,
                'transaction_date' => now(),
                // 'created_by' => auth()->id()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ReturnRequest $returnRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReturnRequest $returnRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReturnRequest $returnRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnRequest $returnRequest)
    {
        //
    }


    public function search(Request $request)
    {
        $invoices = Invoice::where('invoice_number', 'like', '%' . $request->search . '%')
            ->select('id', 'invoice_number', 'paitent_id')
            ->with(['patient:id,name'])
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'text' => $invoice->invoice_number,
                    'patient_id' => $invoice->paitent_id,
                    'patient_name' => $invoice->patient ? $invoice->patient->name : 'Unknown'
                ];
            });

        return response()->json($invoices);
    }

    public function items(Request $request)
    {
        $items = InvoiceDetail::where('invoice_id', $request->invoice_id)
            ->whereHas('item', function ($query) {
                $query->where('item_type', Item::TYPE_PHARMACY);
            })
            ->with(['item:id,name', 'stockTransactions' => function ($query) {
                $query->where('status', StockTransaction::STATUS_OUTGOING_STOCK)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }])
            ->get()
            ->map(function ($item) {
                $originalPrice = $item->stockTransactions->first()?->item_price ?? $item->item_price;
                return [
                    'id' => $item->item->id,
                    'invoice_detail_id' => $item->id,
                    'name' => $item->item->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $originalPrice,
                    'original_price' => $originalPrice
                ];
            });
        return response()->json(['items' => $items]);
    }
}
