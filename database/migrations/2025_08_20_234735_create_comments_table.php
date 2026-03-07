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
        Schema::create('comments', function (Blueprint $table): void {
            $table->increments('id');
            $table->text('content');
            $table->unsignedInteger('task_id');
            $table->unsignedInteger('created_by');
            $table->timestamps();
            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
