<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_dhamen_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // e.g., 'create_supplier', 'update_supplier'
            $table->json('request_data'); // Store the request data
            $table->json('response_data')->nullable(); // Store the API response
            $table->string('status'); // e.g., 'success', 'error'
            $table->text('error_message')->nullable(); // Store error messages if any
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_dhamen_logs');
    }
};
