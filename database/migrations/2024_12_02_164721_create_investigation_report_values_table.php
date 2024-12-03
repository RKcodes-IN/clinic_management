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
        Schema::create('investigation_report_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investigation_report_id'); // Use unsigned for foreign keys
            $table->unsignedBigInteger('investigation_report_type_id'); // Use unsigned for foreign keys
            $table->string('value')->nullable();
            $table->enum('out_of_range', ['yes', 'no'])->default('no'); // Enum column with default value 'no'

            $table->timestamps();

            // Add foreign key constraints (optional but recommended)
            $table->foreign('investigation_report_id')->references('id')->on('investigation_reports')->onDelete('cascade');
            $table->foreign('investigation_report_type_id')->references('id')->on('investigation_report_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investigation_report_values');
    }
};
