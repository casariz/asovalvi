<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_descriptions', function (Blueprint $table) {
            $table->string('status', 10)->primary();
            $table->string('description', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_descriptions');
    }
};