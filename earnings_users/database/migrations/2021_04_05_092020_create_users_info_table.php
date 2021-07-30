<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('name_en');
            $table->string('name_cn');
            $table->string('name_zh');
            $table->bigInteger('reward_point');
            $table->string('delivery_address1');
            $table->string('delivery_address2');
            $table->tinyInteger('status');
            $table->string('contact_no');
            $table->tinyInteger('notification_status');
            $table->string('registered_ip');
            $table->string('recent_ip');
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
        Schema::dropIfExists('users_info');
    }
}
