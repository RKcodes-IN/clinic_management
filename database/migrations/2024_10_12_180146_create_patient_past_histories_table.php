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
        Schema::create('patient_past_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('evalution_id');
            $table->unsignedBigInteger('past_histroy_id');
            $table->float('no_of_years');
            $table->float('trade_name');
            $table->string('chemical');
            $table->string('dose_freq');
            $table->date('date');
            $table->enum('status', ['active', 'inactive', 'delete'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_past_histories');
    }
};
