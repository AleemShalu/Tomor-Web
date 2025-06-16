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
        Schema::create('customer_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('vehicle_manufacturer', 100)->nullable();
            $table->string('vehicle_name', 100)->nullable();
            $table->string('vehicle_model_year', 5)->nullable();
            $table->string('vehicle_color', 50)->nullable();
            $table->string('vehicle_plate_number', 20)->nullable();
            $table->string('vehicle_plate_letters_ar', 20)->nullable();
            $table->string('vehicle_plate_letters_en', 20)->nullable();
            $table->boolean('default_vehicle')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_vehicles');
    }
};
