<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transact', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->bigInteger('users_id')->unsigned()->index();
            $table->decimal('sub_total',11,2);
            $table->decimal('logistic_cost',11,2);
            $table->decimal('downpayment',11,2);
            $table->decimal('deposit',11,2);
            $table->decimal('total_price',11,2);
            $table->bigInteger('logistic_id')->unsigned()->index();
            $table->string('logistic_tracking_no');
            $table->bigInteger('order_status_id')->unsigned()->index();
            $table->dateTime('invoice_at');
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
        Schema::dropIfExists('order_transact');
    }
}
