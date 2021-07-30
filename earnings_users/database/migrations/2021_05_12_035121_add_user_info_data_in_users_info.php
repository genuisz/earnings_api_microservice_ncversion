<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserInfoDataInUsersInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_info', function (Blueprint $table) {
            //
            $table->enum('gender',['M','F'])->nullable()->after('name_zh');
            $table->bigInteger('country_id')->unsigned()->index()->after('status');
            $table->bigInteger('business_nature_id')->unsigned()->index()->after('country_id');
            $table->text('interested_category')->after('business_nature_id');
            $table->text('company_website')->nullable()->after('interested_category');
            $table->text('company_address')->nullable()->after('company_website');
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_info', function (Blueprint $table) {
            //
        });
    }
}
