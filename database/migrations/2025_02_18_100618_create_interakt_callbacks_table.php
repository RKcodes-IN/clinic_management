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
        Schema::create('interakt_callbacks', function (Blueprint $table) {
            $table->id();
            // Extracted details
            $table->string('name')->nullable(); // e.g. data.customer.traits.name
            $table->string('phone_number')->nullable(); // e.g. data.customer.phone_number
            $table->string('status')->nullable(); // e.g. data.message.message_status
            $table->string('failed_reason')->nullable(); // e.g. data.message.channel_failure_reason
            // You can add more columns if needed, for instance:
            $table->string('message_id')->nullable(); // e.g. data.message.id
            $table->timestamp('received_at')->nullable(); // e.g. data.message.received_at_utc

            // Store the entire callback JSON payload
            // Use JSON column type if supported by your DB (or text as fallback)
            $table->json('full_json');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interakt_callbacks');
    }
};
