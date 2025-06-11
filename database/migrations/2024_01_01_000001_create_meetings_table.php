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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id('meeting_id');
            $table->date('meeting_date');
            $table->time('start_hour');
            $table->string('called_by')->nullable();
            $table->string('director')->nullable();
            $table->string('secretary')->nullable();
            $table->string('placement')->nullable();
            $table->text('meeting_description')->nullable();
            $table->string('empty_field')->nullable();
            $table->text('topics')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('creation_date')->nullable();
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};