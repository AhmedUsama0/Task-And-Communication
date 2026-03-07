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
        Schema::create('sprints', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name', 100);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_closed')->default(0);
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprints');
    }
};
