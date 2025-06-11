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
        Schema::create('meeting_topics', function (Blueprint $table) {
            $table->id('topic_id');
            $table->unsignedBigInteger('meeting_id');
            $table->string('type');
            $table->text('topic');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('creation_date')->nullable();
            $table->integer('status')->default(2);
            
            $table->foreign('meeting_id')->references('meeting_id')->on('meetings')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_topics');
    }
};