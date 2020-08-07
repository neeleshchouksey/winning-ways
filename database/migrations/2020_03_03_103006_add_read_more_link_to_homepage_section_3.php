<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadMoreLinkToHomepageSection3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homepage_section_3', function (Blueprint $table) {
            $table->string("read_more_link");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homepage_section_3', function (Blueprint $table) {
            $table->dropColumn("read_more_link");

        });
    }
}
