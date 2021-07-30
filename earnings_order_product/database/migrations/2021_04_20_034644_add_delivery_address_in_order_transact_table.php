<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryAddressInOrderTransactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_transact', function (Blueprint $table) {
            //
            $table->text('delivery_from')->nullable()->after('order_status_id');
            $table->string('destination_country')->nullable()->after('delivery_from');
            $table->text('delivery_address')->nullable()->after('destination_country');
        });
        Schema::table('order_transact_product', function (Blueprint $table) {
            //
            $table->decimal('weight',11,1)->nullable()->after('tracking_no');
            $table->decimal('logistic_cost',11,2)->nullable()->after('weight');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_transact', function (Blueprint $table) {
            //
        });
    }
}
