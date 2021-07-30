<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackingNoInOrderTransactProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_transact_product', function (Blueprint $table) {
            //
            $table->text('tracking_no')->nullable()->after('order_product_status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_transact_product', function (Blueprint $table) {
            //
            $table->dropColumn('tracking_no');
        });
    }
}
