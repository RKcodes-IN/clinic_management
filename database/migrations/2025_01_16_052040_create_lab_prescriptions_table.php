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
        Schema::create('lab_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('paitent_id');
            $table->integer('appointment_id');
            $table->integer('item_id');
            $table->integer('description');
            $table->date('date');
            $table->enum('status', [1, 2]);
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_prescriptions');
    }
};
