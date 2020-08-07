<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCols2ToSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->string("image2");
            $table->text("description2");
            $table->string("image3");
            $table->text("description3");
            $table->string("image4");
            $table->text("description4");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn("image2");
            $table->dropColumn("description2");
            $table->dropColumn("image3");
            $table->dropColumn("description3");
            $table->dropColumn("image4");
            $table->dropColumn("description4");
        });
    }
}
