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
        Schema::create('service_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('code', 20);
            $table->string('description')->nullable();
            $table->string('service_currency_code', 10)->nullable();
            $table->decimal('price', 10, 4)->nullable()->default(0);
            $table->decimal('rate', 10, 4)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_definitions');
    }
};
