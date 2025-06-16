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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_type_id')->nullable()->constrained('business_types')->nullOnDelete();
            $table->string('commercial_name_en')->nullable();
            $table->string('commercial_name_ar')->nullable();
            $table->string('short_name_en')->nullable();
            $table->string('short_name_ar')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('email')->nullable()->unique();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->string('dial_code', 10)->nullable();
            $table->string('contact_no', 30)->nullable();
            $table->string('secondary_dial_code', 10)->nullable();
            $table->string('secondary_contact_no', 30)->nullable();
            $table->string('tax_id_number', 50)->nullable();
            $table->string('tax_id_attachment')->nullable(); // pdf url
            $table->string('commercial_registration_no', 50)->nullable();
            $table->date('commercial_registration_expiry')->nullable();
            $table->string('commercial_registration_attachment')->nullable();  // pdf url
            $table->string('municipal_license_no', 50)->nullable(); // رقم رخصة البلدية
            $table->string('api_url')->nullable();
            $table->string('api_admin_url')->nullable();
            $table->string('menu_pdf')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('store_header')->nullable();
            $table->boolean('status')->default(0);
            $table->unsignedInteger('range_time_order')->default(5); // Default value of 5 minutes
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unique(['dial_code', 'contact_no']);
            $table->unique(['secondary_dial_code', 'secondary_contact_no']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
