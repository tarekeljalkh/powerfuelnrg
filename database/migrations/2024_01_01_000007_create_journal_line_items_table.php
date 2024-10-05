<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalLineItemsTable extends Migration
{
    public function up()
    {
        Schema::create('journal_line_items', function (Blueprint $table) {
            $table->unsignedBigInteger('trans_id');  // Make sure this matches the type in journals table
            $table->unsignedBigInteger('ligne_id');
            $table->unsignedBigInteger('account_code'); // Change from string to unsignedBigInteger
            $table->string('dc_indicator');
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('third_party_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Set composite primary key
            $table->primary(['trans_id', 'ligne_id']);

            // Foreign key constraints
            $table->foreign('trans_id')->references('trans_id')->on('journals')->onDelete('cascade');
            $table->foreign('account_code')->references('account_code')->on('accounts')->onDelete('cascade');
            $table->foreign('third_party_id')->references('id')->on('third_parties');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_line_items');
    }
}
