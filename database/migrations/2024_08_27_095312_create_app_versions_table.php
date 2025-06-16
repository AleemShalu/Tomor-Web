<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('app_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version')->unique();
            $table->string('details_ar')->nullable(); // Arabic details
            $table->string('details_en')->nullable(); // English details
            $table->date('release_date')->nullable(); // Date when the version was released
            $table->boolean('is_mandatory')->default(false); // Indicates if the update is mandatory
            $table->string('download_url')->nullable(); // URL to download the update
            $table->json('platforms')->nullable(); // JSON column to store multiple platforms
            $table->string('release_notes_ar')->nullable(); // Arabic release notes
            $table->string('release_notes_en')->nullable(); // English release notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_versions');
    }
};
