<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("user_id")->unsigned();
            $table->string("name");
            $table->string("phone");
            $table->float("min_budget");
            $table->float("max_budget");
            $table->longText("description");
            $table->string("image");
            $table->tinyInteger("status")->default(0)->comment("0-pending,1-progress,2-deal,3-completed,4-delivered,5-closed");
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('works');
    }
}
