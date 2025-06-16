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
        Schema::create('notifications_group', function (Blueprint $table) {
            $table->id();
            $table->string('notification_type');
            $table->string('platform_type');
            $table->string('users_type');
            $table->string('notification_title_ar')->collation('utf8mb4_unicode_ci');
            $table->text('notification_message_ar')->collation('utf8mb4_unicode_ci');
            $table->string('notification_title_en');
            $table->text('notification_message_en');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications_group');
    }
};
