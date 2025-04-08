<?php

namespace App\Http\Controllers;

use App\DataTables\InvoiceDataTable;
use App\Imports\InvoiceImport;
use App\Models\Appointment;
use App\Models\DoctorDetail;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Item;
use App\Models\PatientDetail;
use App\Models\Stock;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


    public function invoiceToAppointments()
    {
        try {
            // Fetch invoices that have items with id 615 or 481
            $invoices = Invoice::whereHas('details', function ($query) {
                $query->whereNotIn('item_id', [615, 481]);
            })->get();

            foreach ($invoices as $invoice) {
                try {
                    // Use 'paitent_id' instead of 'patient_id'


                    // Find an existing appointment based on paitent_id and confirmation_date
                    $appointment = Appointment::where('patient_id', $invoice->paitent_id)
                        ->whereDate('confirmation_date', '=', $invoice->created_at->toDateString())
                        ->first();

                    // Create an appointment if it doesn't exist
                    if (!$appointment) {
                        $patientDetails = PatientDetail::find($invoice->paitent_id);

                        // Check if patientDetails exists
                        if (!empty($patientDetails)) {
                            Appointment::create([
                                'patient_id' => $invoice->paitent_id,
                                'confirmation_date' => $invoice->created_at,
                                'doctor_id' => $invoice->doctor_id,
                                'email' => $patientDetails->user->email ?? "",
                                'phone_number' => $patientDetails->user->phone ?? "",
                                'address' => $patientDetails->address ?? "",
                                'gender' => $patientDetails->gender ?? "",
                                'city' => $patientDetails->city ?? "",
                                'state' => $patientDetails->state ?? "",
                                'country' => $patientDetails->country ?? "",
                                'whatsapp_no' => $patientDetails->user->phone ?? "",
                                'age' => $patientDetails->age ?? "",
                                'pincode' => "",
                                'status' => Appointment::STATUS_COMPLETED,
                                'created_at' => $invoice->created_at,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    // If any exception occurs for a specific invoice, continue to the next one
                    continue;
                }
            }

            return "done";
        } catch (\Exception $e) {
            // Return a generic error message if something goes wrong in fetching invoices
            return "something went wrong: " . $e->getMessage();
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $patient_id = "")
    {
        // Retrieve patient_id from query string
        // Fetch stocks for pharmacy, lab tests, and miscellaneous
        $pharmacyStocks = Stock::with(['item'])
            ->whereHas('item', function ($query) {
                $query->where('item_type', 1);
            })->get();

        $labTestStocks = Stock::with(['item'])
            ->whereHas('item', function ($query) {
                $query->where('item_type', 3);
            })->get();

        $miscellaneousStocks = Stock::with(['item'])
            ->whereHas('item', function ($query) {
                $query->where('item_type', 2);
            })->get();

        // Fetch patients based on the patient_id query string
        $patients = !empty($patient_id)
            ? PatientDetail::find($patient_id) // Fetch single patient if ID is provided
            : PatientDetail::orderBy('name', 'asc')->get();          // Fetch all patients if no ID is provided

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
        DB::beginTransaction();
        // Create the invoice
        $invoiceNumber = $this->generateInvoiceNumber();
        $invoice = new Invoice();
        // $createdAt = Carbon::createFromFormat('Y-m-d H:i', $request->invoice_date . ' ' . $request->invoice_time);

        // Handle file upload if attachment is provided
        if ($request->hasFile('attachment')) {
            $fileName = time() . '_' . $request->file('attachment')->getClientOriginalName();
            $filePath = $request->file('attachment')->storeAs('attachments', $fileName, 'public');
            $invoice->attachment = '/storage/' . $filePath;
        }
        // $invoice->sub_total = $request->sub;
        $invoice->sub_total = $request->subtotal;
        $invoice->sub_total = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->doctor_id = $request->doctor_id;
        $invoice->gst = $request->gst;
        $invoice->discount = $request->discount;
        $invoice->invoice_number = $invoiceNumber;
        $invoice->paitent_id = $request->patient_id;
        $invoice->payment_status = $request->payment_status;
        $invoice->created_by = Auth::user()->id;
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

        // Check if there are any invoices, and if not, set the invoice number to 1
        if ($lastInvoice) {
            // Extract and increment the invoice number
            $nextInvoiceNumber = $lastInvoice->invoice_number + 1;
        } else {
            // If no invoices exist, start from 1
            $nextInvoiceNumber = 0001;
        }

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
                    'add_dis_percent' => isset($item['add_dis_percent']) ? $item['add_dis_percent'] : 0,
                    'add_dis_amount' => isset($item['discount_amount']) ? $item['discount_amount'] : 0,
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

        // Fetch invoice transactions for payment breakdown
        $invoiceTransactions = \App\Models\InvoiceTransaction::where('invoice_id', $invoice->id)->get();

        // Load view as HTML content and pass the transactions along with other data
        $html = view('invoice.pdf', compact('invoice', 'pharmacyItems', 'invoiceTransactions'))->render();

        // Initialize mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 40,
            'margin_bottom' => 20,
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_header' => 10,
            'margin_footer' => 10,
        ]);
        // Set header with registered address beside the institute name
        $mpdf->SetHTMLHeader('<table style="width:100%; border-collapse: collapse;">
        <tr>
            <td style="width:120px; vertical-align: middle; padding-right: 15px;">
                <img src="https://ik.imagekit.io/phbranchi/logo-ct_dMECUkXSB.png?updatedAt=1734284741500"
                    alt="Institute Logo" style="max-width: 100%; max-height: 70px;">
            </td>
            <td style="vertical-align: middle;">
                <div style="font-size:18px; font-weight:600; color:#1a365d; margin-bottom: 1px;">S.I.V.A.S Health & Research Institute</div>
                <div style="font-size:12px; color:#4a5568; line-height:1.4;">
                    Center for Health Integration of Modern Medicine, Ayurveda & Yoga<br>
                    Center for Eye Diseases | Recognized by Government of Telangana
                </div>
            </td>

            <td style="vertical-align: middle; text-align: right; font-size:12px; color:#4a5568; line-height:1.4;">
                <strong>Reg. Address:</strong> H.No. 10-2-172,<br> St. John\'s Road,
                Opposite <br> Keyes  High School, <br>Secunderabad - 500025<br>
                <strong>Contact:</strong> +91 9848157629 <br> <strong>Email:</strong> sivashri.in@gmail.com
            </td>
        </tr>
    </table>');

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

        $saveInvoice = saveInvoicePayment($invoice->paitent_id, $invoice->id, $request->payment_amount, Invoice::PAYMENT_STATUS_PAID, $request->payment_mode, $request->payment_date);



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
}
