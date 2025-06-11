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
        Schema::create('obligation_payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('obligation_id');
            $table->date('date_ini');
            $table->date('date_end')->nullable();
            $table->decimal('paid', 10, 2);
            $table->text('observations')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('creation_date')->nullable();
            $table->integer('status')->default(2);
            
            $table->foreign('obligation_id')->references('obligation_id')->on('obligations')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obligation_payments');
    }
};