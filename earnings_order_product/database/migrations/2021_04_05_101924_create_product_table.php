<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->unsigned()->index();
            $table->bigInteger('factory_id')->unsigned()->index();
            $table->string('name_en');
            $table->string('name_zh');
            $table->string('name_cn');
            $table->string('product_no',255)->unique();
            $table->string('description');
            $table->bigInteger('quantity_per_log')->unsigned();
            $table->bigInteger('quantity_of_log')->unsigned();
            $table->bigInteger('quantity_unit_id')->unsigned();
            $table->bigInteger('quantity_reach_target_in_log')->unsigned();
            $table->decimal('price',11,2);
            $table->tinyInteger('on_sale');
            $table->bigInteger('product_status_type_id')->unsigned()->index();
            $table->integer('tolerance')->unsigned();
            $table->dateTime('leadtime')->nullable();
            $table->integer('downpayment_ratio')->unsigned();
            $table->integer('deposit_ratio')->unsigned();
            $table->dateTime('duedate')->nullable();
            $table->string('image_url');
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
        Schema::dropIfExists('product');
    }
}
