<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('blog_user_id');
            $table->integer('blog_id');
            $table->string('access_token');
            $table->string('provider');
            $table->string('name');
            $table->string('username');
            $table->string('email');
            $table->string('avatar');
            $table->string('language');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }

}
