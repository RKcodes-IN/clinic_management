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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id');
            $table->integer(column: 'old_invoice_detail_id');
            $table->integer('paitent_id');
            $table->integer('stock_id');
            $table->integer('item_id');
            $table->integer('item_type');
            $table->float('item_price');
            $table->float('total_amount');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
