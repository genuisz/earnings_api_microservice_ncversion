<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('product', function (Blueprint $table) {
            //
            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('factory_id')->references('id')->on('factory');
            $table->foreign('quantity_unit_id')->references('id')->on('quantity_unit');
            $table->foreign('product_status_type_id')->references('id')->on('product_status_type');
        });

        Schema::table('order_transact_product', function (Blueprint $table) {
            $table->foreign('order_transact_id')->references('id')->on('order_transact');
            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('order_product_status_id')->references('id')->on('order_status');

        });
        Schema::table('order_transact', function (Blueprint $table) {
            //$table->foreign('users_id')->references('id')->on('users');
            $table->foreign('logistic_id')->references('id')->on('logistic');
            $table->foreign('order_status_id')->references('id')->on('order_status');
        });
        Schema::table('factory', function (Blueprint $table) {
            //$table->foreign('id')->references('id')->on('back_end_user');
            $table->foreign('country_id')->references('id')->on('country');
            
        });
        Schema::table('category', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('category');
           
            
        });
        Schema::table('product_comment_like', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('product');
            //$table->foreign('users_id')->references('id')->on('users');

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

        Schema::table('product', function (Blueprint $table) {
            //
            $table->dropForeign('product_category_id_foreign');
            $table->dropForeign('product_factory_id_foreign');
            $table->dropForeign('product_quantity_unit_id_foreign');
            $table->dropForeign('product_product_status_type_id_foreign');
        });
        Schema::table('order_transact_product', function (Blueprint $table) {
            $table->dropForeign('order_transact_product_order_transact_id_foreign');
            $table->dropForeign('order_transact_product_product_id_foreign');
            $table->dropForeign('order_transact_product_order_product_status_id_foreign');

        });
        Schema::table('order_transact', function (Blueprint $table) {
            //$table->foreign('order_transact_users_id_foreign')->references('id')->on('users');
            $table->dropForeign('order_transact_logistic_id_foreign');
            $table->dropForeign('order_transact_order_status_id_foreign');
        });
        Schema::table('factory', function (Blueprint $table) {
            //$table->dropForeign('factory_id_foreign')->references('id')->on('back_end_user');
            $table->dropForeign('factory_country_id_foreign');
            
        });
        Schema::table('category', function (Blueprint $table) {
            $table->dropForeign('category_parent_id_foreign');
           
            
        });
        Schema::table('product_comment_like', function (Blueprint $table) {
            $table->dropForeign('product_comment_like_product_id_foreign');
            //$table->foreign('users_id')->references('id')->on('users');

        });
    }
}
