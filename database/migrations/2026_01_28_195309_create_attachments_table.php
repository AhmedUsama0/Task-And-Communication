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
        Schema::create('attachments', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('path');
            // We can use $table->foreignId('message_id')->constrained()->cascadeOnDelete->cascadeOnUpdate()
            // but we use foreign() to make unsigned int instead of big int
            $table->unsignedInteger('message_id')->nullable();
            $table->foreign('message_id')->references('id')->on('messages')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
