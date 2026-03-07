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
        Schema::create('tasks', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('status_id')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('sprint_id');
            $table->unsignedInteger('assigned_to')->nullable();
            $table->unsignedInteger('time_remaining')->default(0);
            $table->unsignedInteger('time_spent')->default(0);
            $table->timestamps();
            $table->foreign('assigned_to')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate()->noActionOnDelete();
            $table->foreign('sprint_id')->references('id')->on('sprints')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('status_id')->references('id')->on('statuses')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
