<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('ar_name');
            $table->string('en_name');
            $table->timestamps();
        });

        Schema::create('report_subtypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_type_id')->nullable()->constrained('report_types')->nullOnDelete();
            $table->string('ar_name')->nullable();
            $table->string('en_name')->nullable();
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(Str::uuid()); // Set a default value using Str::uuid()
            $table->string('ticket_id')->unique()->nullable(); // Laravel will automatically create an auto-incrementing primary key 'id' field
            $table->foreignId('report_subtype_id')->nullable()->constrained('report_subtypes')->nullOnDelete()->cascadeOnUpdate();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->timestamp('submission_time')->useCurrent();
            $table->String('status', 50)->nullable()->comment('Pending Review, In Progress, Resolved');
            $table->string('report_title')->nullable();
            $table->text('body_message')->nullable();
            $table->text('body_reply')->nullable();
            $table->timestamp('resolved_time')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->string('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('report_subtypes');
        Schema::dropIfExists('report_types');
    }
};
