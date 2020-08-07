<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubcategoryVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcategory_videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("subcategory_id")->unsigned();
            $table->string("title");
            $table->string("link");
            $table->timestamps();
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subcategory_videos');
    }
}
