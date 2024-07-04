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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->string('email');
            $table->string('phone_number');
            $table->text('address');
            $table->boolean('is_previous_report_available');
            $table->text('main_complaint');
            $table->date('available_date');
            $table->time('time_from');
            $table->time('time_to');
            $table->text('message')->nullable();
            $table->enum('status', [1, 2, 3, 4]);
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patient_details')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctor_details')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
