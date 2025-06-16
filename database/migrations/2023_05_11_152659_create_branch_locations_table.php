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
        Schema::create('branch_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_branch_id')->nullable()->constrained('store_branches')->cascadeOnDelete();
            $table->string('location_description')->nullable();
            $table->string('location_radius')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('district')->nullable();
            $table->string('national_address', 50)->nullable();
            $table->string('google_maps_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_locations');
    }
};
