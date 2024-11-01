<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'item_code_name',
        'invoice_number',
        'purchase_invoice_date',
        'source_name',
        'brand',
        'category',
        'batch',
        'expiry_date',
        'hsn_code',
        'uom_type',
        'mrp',
        'discount_percentage',
        'discount_price',
        'additional_discount_percentage',
        'additional_discount_price',
        'gst_type',
        'gst_amount',
        'cost_price',
        'courier_price_percentage',
        'courier_charge_amount',
        'final_cost_price',
        'sale_price',
        'sale_discount',
        'profit_margin',
        'purchase_quantity',
        'status',
    ];
}
