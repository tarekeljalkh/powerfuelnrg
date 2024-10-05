<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalsTable extends Migration
{
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id('trans_id');  // Makes trans_id a big integer primary key
            $table->string('trans_code');
            $table->unsignedBigInteger('type_id');
            $table->string('manual_ref')->nullable();
            $table->timestamp('trans_date')->nullable();
            $table->timestamp('activation_date')->nullable();
            $table->boolean('locked')->default(false);
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('transaction_types');
            $table->foreign('parent_id')->references('trans_id')->on('journals');  // Use trans_id here to match the PK
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            });
    }

    public function down()
    {
        Schema::dropIfExists('journals');
    }
}
