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
        Schema::create('usher_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnUpdate();
            $table->foreignId('usher_id')->nullable()->constrained('ushers')->cascadeOnUpdate();

            $table->string('code_usher_used');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usher_clients');
    }
};
