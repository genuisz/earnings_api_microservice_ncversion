<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignRatingIdInOrderTransactProduct extends Migration
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
            $table->foreign('col1_rating_id')->references('id')->on('rating');
            $table->foreign('col2_rating_id')->references('id')->on('rating');
            $table->foreign('col3_rating_id')->references('id')->on('rating');
            $table->foreign('col4_rating_id')->references('id')->on('rating');
            $table->foreign('col_combine_id')->references('id')->on('rating_column');
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
        });
    }
}
