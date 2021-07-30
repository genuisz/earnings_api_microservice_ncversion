<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDestinationPortId extends Migration
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
            $table->bigInteger('to_port_id')->unsigned()->index()->after('logistic_id');
        });
        Schema::table('product', function (Blueprint $table) {
            $table->bigInteger('from_port_id')->unsigned()->index()->after('factory_id');
        });
        Schema::create('logistic_port', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en');
            $table->string('name_cn');
            $table->string('name_zh');
            $table->string('slug');
            $table->bigInteger('country_id')->unsigned()->index();
            $table->bigInteger('port_type_id')->unsigned()->index();
        });
        Schema::create('logistic_port_logistic', function (Blueprint $table) {
            $table->bigInteger('logistic_id')->unsigned()->index();
            $table->bigInteger('from_port_id')->unsigned()->index();
            $table->bigInteger('to_port_id')->unsigned()->index();
            $table->primary(array('logistic_id','to_port_id','from_port_id'),'logistic_port_logistic_primary');
            $table->decimal('courier_charges_unit',15,2);
        });
        Schema::create('port_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en');
            $table->string('name_cn');
            $table->string('name_zh');
            $table->string('slug');

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
