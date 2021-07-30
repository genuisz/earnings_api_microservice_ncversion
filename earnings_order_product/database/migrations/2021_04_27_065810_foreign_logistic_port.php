<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignLogisticPort extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_transact', function (Blueprint $table) {
            //

            $table->foreign('to_port_id')->references('id')->on('logistic_port');
        });
        Schema::table('product', function (Blueprint $table) {
            //

            $table->foreign('from_port_id')->references('id')->on('logistic_port');
        });
        Schema::table('logistic_port', function (Blueprint $table) {
            //
            $table->foreign('country_id')->references('id')->on('country');
            $table->foreign('port_type_id')->references('id')->on('port_type');
        });
        Schema::table('logistic_port_logistic', function (Blueprint $table) {
            $table->foreign('logistic_id')->references('id')->on('logistic');
            $table->foreign('from_port_id')->references('id')->on('logistic_port');
            $table->foreign('to_port_id')->references('id')->on('logistic_port');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
