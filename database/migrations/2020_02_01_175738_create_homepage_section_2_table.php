<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomepageSection2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homepage_section_2', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title1");
            $table->text("description1");
            $table->string("title2");
            $table->text("description2");
            $table->string("title3");
            $table->text("description3");
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
        Schema::dropIfExists('homepage_section_2');
    }
}
