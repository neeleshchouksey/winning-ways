<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->bigInteger("category")->unsigned();
            $table->bigInteger("sub_category")->unsigned();
            $table->foreign('category')->references('id')->on('categories')->onDelete("cascade");
            $table->foreign('sub_category')->references('id')->on('sub_categories')->onDelete("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn("category");
            $table->dropColumn("sub_category");
        });
    }
}
