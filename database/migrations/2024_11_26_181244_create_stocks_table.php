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
            $table->integer('purchase_order_id');
            $table->integer('purchase_order_item_id');
            $table->integer('item_id');
            $table->float('order_quantity');
            $table->float( 'item_price');
            $table->float( 'total_price');
            $table->dateTime('order_date');
            $table->dateTime('received_date');
            $table->dateTime('expiry_date');
            $table->tinyInteger('status')->comment('1 = In Stock, 2 = Out of Stock, 3 = Expired');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
