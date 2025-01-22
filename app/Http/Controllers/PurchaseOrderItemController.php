<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PurchaseOrderItemsImport;
use App\Models\PurchaseOrder;
use App\Models\SourceCompany;
use Mpdf\Mpdf;

class PurchaseOrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new PurchaseOrderItemsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Purchase Order Item Imported!');
    }
    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrderItem $purchaseOrderItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrderItem $purchaseOrderItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrderItem $purchaseOrderItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrderItem $purchaseOrderItem)
    {
        //
    }
}
