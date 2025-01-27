<?php

namespace App\Imports;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Item;
use App\Models\LabPrescription;
use App\Models\PharmacyPrescription;
use App\Models\Stock;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoiceDetailImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        DB::transaction(function () use ($row) {
            // Validate required fields
            $this->validateRequiredFields($row);

            // Retrieve Invoice
            $invoice = Invoice::where('old_invoice_id', $row['old_invoice_id'])->first();
            if (!$invoice) return null;

            // Parse dates using strtotime()
            $parsedDates = [
                'created_at' => $this->parseDate($row['created_at'] ?? null),
                'expiry_date' => $this->parseDate($row['expiry_date'] ?? null)
            ];

            // Item management
            $item = $this->handleItem($row);

            // Stock management
            $stock = $this->handleStock($item, $row, $parsedDates['expiry_date']);

            // Create invoice detail
            $invoiceDetail = $this->createInvoiceDetail($invoice, $item, $stock, $row, $parsedDates['created_at']);

            // Handle stock transaction
            $this->createStockTransaction($stock, $item, $invoiceDetail, $row, $parsedDates['created_at']);

            // Handle prescriptions
            // $this->handlePrescriptions($row, $stock, $item, $invoice->paitent_id, $parsedDates['created_at']);
        });
    }

    private function validateRequiredFields(array $row)
    {
        $required = ['old_invoice_id', 'item_code', 'quantity', 'mrp'];
        foreach ($required as $field) {
            if (!isset($row[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
    }

    private function parseDate($excelDate)
    {
        if (empty($excelDate)) return now();

        try {
            // Handle Excel numeric date format
            if (is_numeric($excelDate)) {
                $unixTimestamp = ($excelDate - 25569) * 86400; // Convert Excel to Unix
                return Carbon::createFromTimestamp($unixTimestamp);
            }

            // Parse using strtotime
            $timestamp = strtotime($excelDate);
            if ($timestamp === false) throw new \Exception("Invalid date format");

            return Carbon::createFromTimestamp($timestamp);
        } catch (\Exception $e) {
            report($e);
            return now();
        }
    }

    private function handleItem(array $row)
    {
        return Item::firstOrCreate(
            ['item_code' => $row['item_code']],
            [
                'name' => $row['item_name'] ?? 'New Item ' . time(),
                'description' => $row['item_description'] ?? null
            ]
        );
    }

    private function handleStock(Item $item, array $row, Carbon $expiryDate)
    {
        $quantity = (float)$row['quantity'];
        $mrp = (float)$row['mrp'];

        $stock = Stock::where('item_id', $item->id)
            ->where('expiry_date', $expiryDate)
            ->first();

        if ($stock) {
            $stock->update([
                'order_quantity' => $stock->order_quantity + $quantity,
                'total_price' => $stock->total_price + ($quantity * $mrp)
            ]);
            return $stock;
        }

        return Stock::create([
            'item_id' => $item->id,
            'order_quantity' => $quantity,
            'item_price' => $mrp,
            'total_price' => $quantity * $mrp,
            'status' => 1,
            'expiry_date' => $expiryDate,
            'batch_no' => $row['batch'] ?? 'BATCH-' . time(),
        ]);
    }

    private function createInvoiceDetail(Invoice $invoice, Item $item, Stock $stock, array $row, Carbon $createdAt)
    {
        return InvoiceDetail::create([
            'invoice_id' => $invoice->id,
            'old_invoice_id' => $row['old_invoice_id'],
            'old_invoice_detail_id' => $row['old_invoice_detail_id'] ?? null,
            'paitent_id' => $invoice->paitent_id ?? 0,
            'item_id' => $item->id,
            'stock_id' => $stock->id,
            'item_type' => 0,
            'item_price' => (float)$row['mrp'],
            'discount_amount' => (float)($row['discount_amount'] ?? 0),
            'add_dis_amount' => (float)($row['add_dis_amount'] ?? 0),
            'add_dis_percent' => (float)($row['add_dis_percent'] ?? 0),
            'total_amount' => (float)$row['amount'],
            'created_at' => $createdAt,
        ]);
    }

    private function createStockTransaction(Stock $stock, Item $item, InvoiceDetail $invoiceDetail, array $row, Carbon $transactionDate)
    {
        StockTransaction::create([
            'stock_id' => $stock->id,
            'item_id' => $item->id,
            'invoice_id' => $invoiceDetail->id,
            'quantity' => (float)$row['quantity'],
            'item_price' => (float)$row['mrp'],
            'total_price' => (float)$row['quantity'] * (float)$row['mrp'],
            'status' => 2,
            'transaction_date' => $transactionDate,
        ]);
    }

    // private function handlePrescriptions(array $row, Stock $stock, Item $item, $patientId, Carbon $date)
    // {
    //     $prescriptionData = [
    //         'stock_id' => $stock->id,
    //         'item_id' => $item->id,
    //         'patient_id' => $patientId,
    //         'quantity' => (float)$row['quantity'],
    //         'description' => $row['description'] ?? '',
    //         'date' => $date
    //     ];

    //     match ($row['item_type']) {
    //         Item::TYPE_PHARMACY => PharmacyPrescription::create($prescriptionData),
    //         Item::TYPE_LAB => LabPrescription::create($prescriptionData),
    //         default => null
    //     };
    // }
}
