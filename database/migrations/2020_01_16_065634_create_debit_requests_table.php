<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("user_id")->unsigned();
            $table->string("amount");
            $table->text("comment");
            $table->tinyInteger("status")->default(0)->comment("0-pending,1-accepted");
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debit_requests');
    }
}
