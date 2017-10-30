<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Helpers\UserTypes;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('auth0_id')->nullable();
            $table->string('invite_token');
            $table->string('invite_email');
            $table->enum('type', UserTypes::toArray())->default(UserTypes::STUDENT);
            $table->string('calender_token');
            $table->string('picture')->nullable();
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
        Schema::dropIfExists('users');
    }
}
