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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->unsignedBigInteger('meeting_id')->nullable();
            $table->date('start_date');
            $table->integer('estimated_time');
            $table->string('units');
            $table->text('task_description');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->text('observations')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('creation_date')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('review_date')->nullable();
            $table->unsignedBigInteger('status')->default(1); // Pendiente

            // Foreign key constraints
            $table->foreign('meeting_id')->references('meeting_id')->on('meetings')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');

            // CORREGIDO: Apuntar a status_descriptions y a la columna status
            $table->foreign('status')
                ->references('status')
                ->on('status_descriptions')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
