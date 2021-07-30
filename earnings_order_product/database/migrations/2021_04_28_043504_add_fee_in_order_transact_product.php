<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeeInOrderTransactProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_transact_product', function (Blueprint $table) {

            $table->decimal('sub_total',11,2)->after('deposit');

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
            $table->dropColumn('deposit');
        });
    }
}
