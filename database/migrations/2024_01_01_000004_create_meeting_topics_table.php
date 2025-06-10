<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_topics', function (Blueprint $table) {
            $table->id('topic_id');
            $table->unsignedBigInteger('meeting_id');
            $table->string('type', 50);
            $table->text('topic');
            $table->unsignedBigInteger('created_by');
            $table->datetime('creation_date');
            $table->string('status', 10);
            
            $table->foreign('meeting_id')->references('meeting_id')->on('meetings');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('status')->references('status')->on('status_descriptions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_topics');
    }
};