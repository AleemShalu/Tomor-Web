<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('vat_number', 20)->nullable();
            $table->string('group_vat_number', 20)->nullable();
            $table->string('cr_number', 15);
            $table->string('email')->unique();
            $table->string('country_code', 10)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('country', 50);
            $table->string('state', 50)->nullable();
            $table->string('city', 50);
            $table->string('district', 50)->nullable();
            $table->string('street', 100)->nullable();
            $table->string('building_no', 20)->nullable();
            $table->string('zipcode', 15)->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->boolean('status')->default(0);
            $table->unique(['country_code', 'phone']);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
};
