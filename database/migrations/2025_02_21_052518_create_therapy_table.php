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
        Schema::create('therapy', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('patient_id'); // Foreign key for patients
            $table->unsignedBigInteger('appointment_id'); // Foreign key for appointments
            $table->unsignedBigInteger('item_id'); // Foreign key for items
            $table->string('sub_category'); // varchar field for sub category
            $table->string('status'); // varchar field for status


            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapy');
    }
};
