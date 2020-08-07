<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkFollowupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_followup', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("work_id")->unsigned();
            $table->longText("comment");
            $table->bigInteger("next_followup_date");
            $table->foreign('work_id')->references('id')->on('works')->onDelete("cascade");
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
        Schema::dropIfExists('work_followup');
    }
}
