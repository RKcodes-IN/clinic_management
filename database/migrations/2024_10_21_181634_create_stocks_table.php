<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('item_code_name');  // fixed typo
            $table->string('invoice_number');
            $table->date('purchase_invoice_date');
            $table->integer('source_name');  // changed int() to integer()
            $table->integer('brand');  // changed int() to integer()
            $table->integer('category');  // changed int() to integer()
            $table->string('batch');
            $table->date('expiry_date');
            $table->string('hsn_code');
            $table->integer('uom_type');  // changed int() to integer()
            $table->float('mrp');
            $table->float('discount_percentage');
            $table->float('discount_price');
            $table->float('additional_discount_percentage');
            $table->float('additional_discount_price');
            $table->integer('gst_type');  // changed int() to integer()
            $table->float('gst_amount');
            $table->float('cost_price');
            $table->float('courier_price_percentage');  // fixed typo
            $table->float('courier_charge_amount');
            $table->float('final_cost_price');
            $table->float('sale_price');
            $table->float('sale_discount');
            $table->float('profit_margin');
            $table->float('purchase_quantity');
            $table->enum('status', ['active', 'inactive', 'delete'])->default('active');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
