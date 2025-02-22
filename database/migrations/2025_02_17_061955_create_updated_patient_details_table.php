<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('updated_patient_details', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name as per Aadhaar (changeable)
            $table->integer('age'); // Age as on (changeable)
            $table->date('dob')->nullable();
            $table->string('email')->unique();
            $table->string('country_code'); // Mandatory country code (placed before phone)
            $table->string('phone_number1'); // Phone number 1
            $table->string('alt_contact')->nullable();
            $table->text('address')->nullable();
            $table->string('city');
            $table->string('country')->default('India'); // Default value is India
            $table->string('pincode')->nullable();
            $table->string('image')->nullable(); // Store file path if image uploaded
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('updated_patient_details');
    }
};
