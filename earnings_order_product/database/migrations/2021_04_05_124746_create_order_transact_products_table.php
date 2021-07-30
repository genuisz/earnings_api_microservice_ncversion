<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransactProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transact_product', function (Blueprint $table) {
            $table->bigInteger('product_id')->unsigned()->index();
            $table->string('order_transact_id')->index();
            $table->primary(['product_id','order_transact_id']);
            $table->string('unique_order_product_id',255)->unique();
            $table->bigInteger('order_quantity_in_log')->unsigned();
            $table->bigInteger('order_product_status_id')->unsigned()->index();
            $table->dateTime('confirmed_delivery_date');
            $table->dateTime('expected_ETD');
            $table->dateTime('due_at');
            $table->decimal('downpayment',11,2);
            $table->decimal('deposit',11,2);
            $table->decimal('total',11,2);
            $table->string('feedback');


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
        Schema::dropIfExists('order_transact_product');
    }
}
