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
        Schema::create('store_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('branch_serial_number', 50)->nullable();
            $table->string('qr_code')->nullable();
            $table->string('commercial_registration_no', 50)->nullable();
            $table->date('commercial_registration_expiry')->nullable();
            $table->string('commercial_registration_attachment')->nullable();
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('dial_code', 10)->nullable();
            $table->string('contact_no', 30)->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->boolean('default_branch')->default(0);
            // $table->boolean('status')->default(0);
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
            // $table->unique(['dial_code', 'contact_no']);
            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_branches');
    }
};
