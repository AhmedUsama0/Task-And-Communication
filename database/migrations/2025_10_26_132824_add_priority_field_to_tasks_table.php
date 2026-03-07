<?php

use App\Enums\TaskPriority;
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
        Schema::table('tasks', function (Blueprint $table): void {
            $table->enum('priority', [
                TaskPriority::Low->value,
                TaskPriority::Medium->value,
                TaskPriority::High->value,
                TaskPriority::Critical->value,
            ])
                ->default(TaskPriority::Low->value)
                ->after('time_spent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            //
        });
    }
};
