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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('paitent_id');
            $table->integer('appointment_id');
            $table->string('invoice_number');
            $table->date('date' );
            $table->integer('doctor_id');
            $table->float('sub_total');
            $table->float('other');
            $table->float('total');
            $table->float('recieved_amount');
            $table->float('pending_amount');
            $table->integer('payment_status');
            $table->integer('created_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
