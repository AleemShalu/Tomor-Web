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
        Schema::create('store_terms', function (Blueprint $table) {
            $table->id();
            $table->longText('body_ar')->nullable();
            $table->longText('body_en')->nullable();
            $table->dateTime('issued_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_terms');
    }
};
