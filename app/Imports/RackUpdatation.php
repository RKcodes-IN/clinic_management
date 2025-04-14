<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class RackUpdatation implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Find existing item by item_code
        $item = Item::where('item_code', $row['item_code'])->first();

        if ($item) {
            if (!empty($row['rack']) && trim($row['rack']) !== '') {
                $item->rack = $row['rack'];
                $item->save();
            }
        }

        $stock = Stock::where('id', $row['id'])->first();

        if ($stock) {
            // Handle correct_exp with strtotime
            $correctExp = null;
            if (!empty($row['correct_exp']) && trim($row['correct_exp']) !== '') {
                Log::info('Processing correct_exp: ' . $row['correct_exp']); // Log raw input

                // Use strtotime to parse the date
                $timestamp = strtotime($row['correct_exp']);
                if ($timestamp !== false && $timestamp > 0) { // Check for valid parsing and non-epoch date
                    $correctExp = date('Y-m-d', $timestamp); // Format as YYYY-MM-DD
                    Log::info('Parsed correct_exp: ' . $correctExp);
                } else {
                    Log::warning('Failed to parse correct_exp: ' . $row['correct_exp'] . ' - Invalid date');
                    $correctExp = null; // Set to null if parsing fails
                }
            } else {
                Log::info('correct_exp is empty or whitespace for ID: ' . $row['id']);
            }

            // Update stock
            $stock->expiry_date = $correctExp; // Will be null or valid date
            $stock->item_price = $row['correct_price'];
            $stock->correct_stock = $row['correct_stock'];
            $stock->delete_yn = $row['delete_yn'];
            $stock->save();

            Log::info('Saved correct_exp for ID ' . $row['id'] . ': ' . ($stock->correct_exp ?? 'NULL'));

            return $stock;
        }

        return null;
    }

    /**
     * Define validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'item_code' => 'required|string',
            'rack' => 'required|string',
            'correct_exp' => 'nullable|date', // Allow null, validate as date
            'correct_price' => 'nullable|numeric',
            'correct_stock' => 'nullable|numeric',
            'delete_yn' => 'nullable|string',
        ];
    }

    /**
     * Custom validation messages
     *
     * @return array
     */
}
