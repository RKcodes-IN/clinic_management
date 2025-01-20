<?php

namespace App\Http\Controllers;

use App\DataTables\InvoiceDataTable;
use App\Imports\InvoiceImport;
use App\Models\DoctorDetail;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Item;
use App\Models\LabPrescription;
use App\Models\PatientDetail;
use App\Models\PharmacyPrescription;
use App\Models\Stock;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InvoiceDataTable $dataTable)
    {
        $status = request()->get('status');
        return $dataTable->with('status', $status)->render('invoice.index');
    }


    public function importForm(Request $request)
    {
        return view('invoice.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv', // Validate file type
        ]);

        // Import the Excel file
        Excel::import(new InvoiceImport, $request->file('file'));

        return redirect()->back()->with('success', 'Import Form');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Retrieve patient_id from query string
        $patient_id = $request->query('patient_id');

        // Fetch stocks for pharmacy, lab tests, and miscellaneous
        $pharmacyStocks = Stock::with(['item'])
            ->whereHas('item', function ($query) {
                $query->where('item_type', 1);
            })->get();

        $labTestStocks = Stock::with(['item'])
            ->where('status', Stock::IN_STOCK) // Add condition for status
            ->whereHas('item', function ($query) {
                $query->where('item_type', 3); // Condition for item_type
            })
            ->get();

        $miscellaneousStocks = Stock::with(['item'])
            ->where('status', Stock::IN_STOCK) // Add condition for status

            ->whereHas('item', function ($query) {
                $query->where('item_type', 2);
            })->get();

        // Fetch patients based on the patient_id query string
        $patients = !empty($patient_id)
            ? PatientDetail::find($patient_id) // Fetch single patient if ID is provided
            : PatientDetail::all();          // Fetch all patients if no ID is provided

        // Fetch all doctors
        $doctors = DoctorDetail::all();

        // Return the view with the necessary data
        return view('invoice.create', compact('pharmacyStocks', 'labTestStocks', 'miscellaneousStocks', 'patients', 'doctors'));
    }


    public function searchItems(Request $request)
    {
        $search = $request->input('q'); // Get the search query

        $items = Item::where(function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('item_code', 'LIKE', "%{$search}%");
        })->get();

        return response()->json($items);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        // $validatedData = $request->validate([
        //     'invoice_date' => 'required|date',
        //     'invoice_time' => 'required',


        // ]);
        // Start database transaction
        // dd($request);
        DB::beginTransaction();
        // Create the invoice
        $invoiceNumber = $this->generateInvoiceNumber();
        $invoice = new Invoice();
        $createdAt = Carbon::createFromFormat('Y-m-d H:i', $request->invoice_date . ' ' . $request->invoice_time);

        // Handle file upload if attachment is provided
        if ($request->hasFile('attachment')) {
            $fileName = time() . '_' . $request->file('attachment')->getClientOriginalName();
            $filePath = $request->file('attachment')->storeAs('attachments', $fileName, 'public');
            $invoice->attachment = '/storage/' . $filePath;
        }
        // $invoice->sub_total = $request->sub;
        $invoice->created_at = $createdAt;
        $invoice->sub_total = $request->subtotal;
        $invoice->sub_total = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->gst = $request->gst;
        $invoice->invoice_number = $invoiceNumber;
        $invoice->paitent_id = $request->patient_id;
        $invoice->payment_status = $request->payment_status;
        $invoice->save();

        // Process invoice items
        $this->processInvoiceItems($request->pharmacy, $invoice, 1, $request->patient_id); // Pharmacy items
        $this->processInvoiceItems($request->labtests, $invoice, 2, $request->patient_id); // Lab tests
        $this->processInvoiceItems($request->misc, $invoice, 3, $request->patient_id); // Miscellaneous items

        // Commit transaction
        DB::commit();

        return redirect()->route('invoice.show', $invoice->id)->with('success', 'Invoice created successfully.');
    }

    private function generateInvoiceNumber()
    {
        // Get the last invoice number from the invoices table
        $lastInvoice = Invoice::orderBy('invoice_number', 'desc')->first();

        // Extract and increment the invoice number
        $nextInvoiceNumber = $lastInvoice->invoice_number + 1;

        // Format the invoice number (e.g., INV00001)
        return str_pad($nextInvoiceNumber, 5, '0', STR_PAD_LEFT);
    }
    /**
     * Process and save invoice items.
     *
     * @param array|null $items
     * @param Invoice $invoice
     * @param int $itemType
     * @return void
     */
    private function processInvoiceItems(?array $items, Invoice $invoice, int $itemType, $patient_id)
    {
        if (!$items) {
            return;
        }

        foreach ($items as $item) {

            try {
                $stock = Stock::findOrFail($item['item_id']);

                $invoiceItem = new InvoiceDetail([
                    'invoice_id' => $invoice->id,
                    'old_invoice_id' => 0,
                    'old_invoice_detail_id' => 0,
                    'paitent_id' => $patient_id,
                    'stock_id' => $stock->id,
                    'item_type' => $itemType,
                    'item_id' => $stock->item_id,
                    'quantity' => $item["quantity"],
                    'item_price' => $item['price'],
                    'total_amount' => $item['total'],
                    'description' => $item['description'] ?? null,
                    'transaction_date' => now(),
                ]);

                $invoiceItem->save();

                // Record stock transaction
                $stockTransaction = new StockTransaction([
                    'invoice_id' => $invoiceItem->id,
                    'stock_id' => $stock->id,
                    'item_id' => $stock->item_id,
                    'quantity' => $invoiceItem->quantity,
                    'item_price' => $invoiceItem->item_price,
                    'total_price' => $invoiceItem->total_amount,
                    'status' => StockTransaction::STATUS_OUTGOING_STOCK,
                    'transaction_date' => now(),
                ]);

                $stockTransaction->save();
            } catch (\Exception $e) {
                // Log error for this specific item
                Log::error("Error processing item [Type: Item ID:" . $e->getMessage());
                throw $e; // Rethrow to trigger rollback
            }
        }
    }


    public function downloadInvoice($id)
    {
        $invoice = Invoice::with(['patient', 'doctor', 'details.stock.item'])->findOrFail($id);

        $pharmacyItems = $invoice->details;

        // Load view as HTML content
        $html = view('invoice.pdf', compact('invoice', 'pharmacyItems'))->render();

        // Initialize mPDF
        $mpdf = new \Mpdf\Mpdf();

        // Ensure no header is set
        $mpdf->SetHTMLHeader(''); // Clear any existing header settings

        // Set footer
        $mpdf->SetHTMLFooter('<div style="text-align: center;">Page {PAGENO}</div>');

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF
        return $mpdf->Output('invoice_' . $invoice->invoice_number . '.pdf', 'D');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['patient', 'doctor', 'details.stock.item'])->findOrFail($id);

        $pharmacyItems = $invoice->details;

        return view('invoice.show', compact('invoice', 'pharmacyItems'));
    }


    public function updatePayPayment(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        // Retrieve the invoice
        $invoice = Invoice::findOrFail($id);

        // Update payment details
        $invoice->payment_date = $request->payment_date;
        $invoice->payment_mode = $request->payment_mode;
        $invoice->payment_amount += $request->payment_amount; // Add to existing amount
        $invoice->pending_amount = max(0, $invoice->total - $invoice->payment_amount); // Calculate pending amount

        // Update payment status
        if ($invoice->pending_amount == 0 || $invoice->payment_amount == $invoice->total) {
            $invoice->payment_status = Invoice::PAYMENT_STATUS_PAID; // Fully paid
        } else {
            $invoice->payment_status = Invoice::PAYMENT_PARTIAL_PAYMENT; // Partial payment
        }

        // Save the updated invoice
        $invoice->save();

        // Fetch pharmacy items (if applicable)
        $pharmacyItems = $invoice->pharmacyItems ?? collect();

        // Redirect back to the invoice page with success message
        return redirect()->route('invoice.show', $id)
            ->with('success', 'Payment details updated successfully.')
            ->with(compact('invoice', 'pharmacyItems'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    public function getPrescriptions($patientId)
    {
        $pharmacyPrescriptions = PharmacyPrescription::where('patient_id', $patientId)->get();
        $labPrescriptions = LabPrescription::where('patient_id', $patientId)->get();

        $pharmacyData = $pharmacyPrescriptions->map(function ($prescription) {
            return [
                'item_id' => $prescription->stock_id,
                'available_quantity' =>  Stock::getTotalStock($prescription->stock_id),
                'expiry_date' =>  $prescription->stock->expiry_date,
                'price' =>  $prescription->stock->item_price,
                'item_name' => $prescription->item->name,
                'batch_number' => $prescription->stock->batch_no,

                'quantity' => $prescription->quantity,

            ];
        });

        $labData = $labPrescriptions->map(function ($prescription) {
            return [
                'item_id' => $prescription->stock_id,
                'expiry_date' =>  $prescription->stock->expiry_date,
                'price' =>  $prescription->stock->item_price,
                'item_name' => $prescription->item->name,
                'quantity' => $prescription->quantity,

            ];
        });

        return response()->json(['pharmacy' => $pharmacyData, 'lab' => $labData]);
    }
}
