<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInProductCommentLike extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_comment_like', function (Blueprint $table) {
            //
            $table->integer('target_quantity')->nullable();
            $table->decimal('target_price',15,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_comment_like', function (Blueprint $table) {
            //
            $table->dropColumn('target_quantity');
            $table->dropColumn('target_price');
        });
    }
}
