<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingIdInOrderProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_transact_product', function (Blueprint $table) {
            $table->bigInteger('col1_rating_id')->unsigned()->index()->nullable();
            $table->bigInteger('col2_rating_id')->unsigned()->index()->nullable();
            $table->bigInteger('col3_rating_id')->unsigned()->index()->nullable();
            $table->bigInteger('col4_rating_id')->unsigned()->index()->nullable();
            $table->bigInteger('col_combine_id')->unsigned()->index()->nullable();
            
        });
        Schema::create('rating', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('value');
        });
        Schema::create('rating_column', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('col1_en');
            $table->string('col1_cn');
            $table->string('col1_zh');
            $table->string('col2_en');
            $table->string('col2_cn');
            $table->string('col2_zh');
            $table->string('col3_en');
            $table->string('col3_cn');
            $table->string('col3_zh');   
            $table->string('col4_en');
            $table->string('col4_cn');
            $table->string('col4_zh');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product', function (Blueprint $table) {
            //
            
        });
    }
}
