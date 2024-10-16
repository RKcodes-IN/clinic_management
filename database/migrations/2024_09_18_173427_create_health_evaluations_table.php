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
        Schema::create('health_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('appointment_id');
            $table->integer('age');
            $table->float('weight');
            $table->float('height');
            $table->string('occupation');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->integer('working_hours');
            $table->boolean('night_shift'); // true for yes, false for no
            $table->enum('climatic_condition', ['cold', 'hot', 'dusty', 'moist']);
            $table->boolean('allergic_to_drugs'); // true for yes, false for no
            $table->string('allergic_drug_names')->nullable();
            $table->boolean('food_allergies'); // true for yes, false for no
            $table->boolean('lactose_tolerance'); // true for tolerant, false for not tolerant
            $table->boolean('lmp')->nullable(); // true for yes, false for no (applicable for female patients)
            $table->timestamps();

            // Add foreign key to patients table if required
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_evaluations');
    }
};
