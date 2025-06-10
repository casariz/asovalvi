<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obligations', function (Blueprint $table) {
            $table->id('obligation_id');
            $table->text('obligation_description');
            $table->integer('category_id');
            $table->string('server_name', 100);
            $table->decimal('quantity', 10, 2);
            $table->string('period', 20);
            $table->integer('alert_time');
            $table->unsignedBigInteger('created_by');
            $table->date('last_payment')->nullable();
            $table->date('expiration_date');
            $table->text('observations')->nullable();
            $table->string('internal_reference', 50)->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->datetime('review_date')->nullable();
            $table->string('status', 10);
            
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('reviewed_by')->references('id')->on('users');
            $table->foreign('status')->references('status')->on('status_descriptions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obligations');
    }
};