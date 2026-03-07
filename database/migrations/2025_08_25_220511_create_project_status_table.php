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
        Schema::create('project_status', function (Blueprint $table): void {
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('status_id')->references('id')->on('statuses')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_status');
    }
};
