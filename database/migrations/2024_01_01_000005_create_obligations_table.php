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
        Schema::create('obligations', function (Blueprint $table) {
            $table->id('obligation_id');
            $table->text('obligation_description');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('server_name')->nullable();
            $table->integer('quantity');
            $table->string('period');
            $table->integer('alert_time');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->decimal('last_payment', 10, 2)->nullable();
            $table->date('expiration_date')->nullable();
            $table->text('observations')->nullable();
            $table->string('internal_reference')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('review_date')->nullable();
            $table->integer('status')->default(12);
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obligations');
    }
};