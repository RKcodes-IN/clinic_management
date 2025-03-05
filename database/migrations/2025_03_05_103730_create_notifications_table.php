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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // varchar field for sub category
            $table->string('message'); // varchar field for sub category
            $table->string('redirect_url'); // varchar field for sub category
            $table->integer('notification_type'); // varchar field for sub category
            $table->integer('status'); // varchar field for sub category

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
