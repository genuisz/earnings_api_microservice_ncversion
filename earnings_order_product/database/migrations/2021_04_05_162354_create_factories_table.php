<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en');
            $table->string('name_zh');
            $table->string('name_cn');
            $table->bigInteger('country_id')->unsigned()->index();
            $table->string('contact_no');
            $table->string('address');
            $table->string('email');
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
        Schema::dropIfExists('factory');
    }
}
