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
        Schema::create('otp_phone_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('dial_code', 10);
            $table->string('contact_no', 30);
            $table->string('code')->nullable();
            $table->integer('attempts')->default(0);
            $table->string('type', 20);
            $table->index(['dial_code', 'contact_no']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_phone_verifications');
    }
};
