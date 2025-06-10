<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id('meeting_id');
            $table->date('meeting_date');
            $table->time('start_hour');
            $table->unsignedBigInteger('called_by');
            $table->unsignedBigInteger('director');
            $table->unsignedBigInteger('secretary');
            $table->string('placement', 200);
            $table->text('meeting_description');
            $table->string('empty_field', 100)->nullable();
            $table->text('topics')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->datetime('creation_date');
            $table->string('status', 10);
            
            $table->foreign('called_by')->references('id')->on('users');
            $table->foreign('director')->references('id')->on('users');
            $table->foreign('secretary')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('status')->references('status')->on('status_descriptions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};