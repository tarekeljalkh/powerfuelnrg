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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_id'); // This references trans_id in journals
            $table->string('receipt_number');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('payment_method')->nullable();
            $table->unsignedBigInteger('third_party_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('trans_id')->references('trans_id')->on('journals')->onDelete('cascade');
            $table->foreign('third_party_id')->references('id')->on('third_parties')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
