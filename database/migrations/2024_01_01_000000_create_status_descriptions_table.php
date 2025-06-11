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
        Schema::create('status_descriptions', function (Blueprint $table) {
            $table->id('status');
            $table->string('status_name');
            $table->string('status_description')->nullable();
            $table->string('context')->nullable(); // Para indicar si es para tasks, meetings, obligations, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_descriptions');
    }
};