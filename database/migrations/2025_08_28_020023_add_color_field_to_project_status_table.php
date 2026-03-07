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
        Schema::table('project_status', function (Blueprint $table): void {
            $table->string('color', 7)->default('#e5e7eb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_status', function (Blueprint $table): void {
            $table->dropColumn('color');
        });
    }
};
