<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notifiable_type');
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->string('type'); // Add a 'type' column
            $table->json('data'); // Store notification data as JSON
            $table->text('channel')->nullable(); // Allow NULL values for the 'channel' column
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
