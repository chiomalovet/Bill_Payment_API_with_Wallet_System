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
        Schema::create('electricity_payments', function (Blueprint $table) {
        $table->id();
        $table->string('service_provider'); // Provider (e.g., PHCN)
        $table->string('meter_number'); // Customer's meter number
        $table->decimal('amount', 15, 2); // Amount paid
        $table->string('status')->default('pending'); // Status (pending, success, failed)
        $table->json('api_response')->nullable(); // External API response
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electricity_payments');
    }
};
