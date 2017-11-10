<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_chats')) {
            Schema::create('user_chats', function (Blueprint $table) {
                $table->increments('id');
                $table->engine = 'InnoDB';
                $table->integer('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('voluntary_id')->unsigned()->index();
                $table->foreign('voluntary_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');;
                $table->integer('chat_id');
                $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade')->onUpdate('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_chats');
    }
}
