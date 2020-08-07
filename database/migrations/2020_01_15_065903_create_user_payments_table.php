<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("user_id")->unsigned();
            $table->bigInteger("work_id")->unsigned();
            $table->string("amount");
            $table->string("transaction_type");
            $table->tinyInteger("status")->default(0)->comment("0-pending,1-completed");
            $table->text("comments")->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
            $table->foreign('work_id')->references('id')->on('works')->onDelete("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_payments');
    }
}
