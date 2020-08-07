<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("name");
            $table->integer("state_id");
            $table->integer("city_id");
            $table->integer("pin_code");
            $table->longText("address");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("name");
            $table->dropColumn("state_id");
            $table->dropColumn("city_id");
            $table->dropColumn("pin_code");
            $table->dropColumn("address");
        });
    }
}
