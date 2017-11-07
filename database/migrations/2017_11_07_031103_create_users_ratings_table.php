<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rating');
            $table->integer('rated_by');
            $table->foreign('rated_by')->references('id')->on('users');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('posts');
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
        Schema::dropIfExists('users_ratings');
    }
}
