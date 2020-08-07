<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_commission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("package_id")->unsigned();
            $table->bigInteger("subcategory_id")->unsigned();
            $table->integer("commission");
            $table->timestamps();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete("cascade");
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
        Schema::dropIfExists('package_commission');
    }
}
