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
        Schema::create('localized_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_type_id');
            $table->string('locale');
            $table->string('name');
            $table->text('description');
            $table->timestamps();

            $table->foreign('notification_type_id')->references('id')->on('notification_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localized_notifications');
    }
};
