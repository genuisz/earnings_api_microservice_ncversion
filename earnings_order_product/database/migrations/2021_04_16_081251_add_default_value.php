<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_transact',function(Blueprint $table){
            $table->decimal('sub_total',11,2)->default(0)->change();
            $table->decimal('logistic_cost',11,2)->default(0)->change();
            $table->decimal('downpayment',11,2)->default(0)->change();
            $table->decimal('deposit',11,2)->default(0)->change();
            $table->decimal('total_price',11,2)->default(0)->change();
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


        Schema::table('order_transact',function(Blueprint $table){
            $table->decimal('sub_total',11,2)->change();
            $table->decimal('logistic_cost',11,2)->change();
            $table->decimal('downpayment',11,2)->change();
            $table->decimal('deposit',11,2)->change();
            $table->decimal('total_price',11,2)->change();
        });
    }
}
