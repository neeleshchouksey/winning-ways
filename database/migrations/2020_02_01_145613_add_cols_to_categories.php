<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->tinyInteger("offline_services")->default(0)->comment("0-removed,1-added");
            $table->tinyInteger("online_services")->default(0)->comment("0-removed,1-added");
            $table->tinyInteger("category_slider")->default(0)->comment("0-removed,1-added");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn("offline_services");
            $table->dropColumn("online_services");
            $table->dropColumn("category_slider");
        });
    }
}
