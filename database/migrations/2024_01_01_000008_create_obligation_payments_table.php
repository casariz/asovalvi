<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obligation_payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('obligation_id');
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->text('payment_description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->datetime('creation_date');
            $table->string('status', 10);
            
            $table->foreign('obligation_id')->references('obligation_id')->on('obligations');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('status')->references('status')->on('status_descriptions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obligation_payments');
    }
};