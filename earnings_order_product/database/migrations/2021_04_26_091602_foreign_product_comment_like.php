<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignProductCommentLike extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('product_comment_like', function (Blueprint $table) {
            //
            $table->bigInteger('type')->unsigned()->index()->change();
            $table->foreign('type')->references('id')->on('product_comment_like_type');

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
        Schema::table('product_comment_like', function (Blueprint $table) {
            //
            $table->dropForeign('product_comment_like_type_foreign');

        });
        
    }
}
