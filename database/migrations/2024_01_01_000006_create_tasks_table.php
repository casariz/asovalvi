<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->unsignedBigInteger('meeting_id');
            $table->date('start_date');
            $table->integer('estimated_time');
            $table->string('units', 20);
            $table->text('task_description');
            $table->unsignedBigInteger('assigned_to');
            $table->text('observations')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->datetime('creation_date');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->datetime('review_date')->nullable();
            $table->string('status', 10);
            
            $table->foreign('meeting_id')->references('meeting_id')->on('meetings');
            $table->foreign('assigned_to')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('reviewed_by')->references('id')->on('users');
            $table->foreign('status')->references('status')->on('status_descriptions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};