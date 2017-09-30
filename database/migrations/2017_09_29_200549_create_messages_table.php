<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
        if (!Schema::hasTable('messages')) {
             Schema::create('messages', function (Blueprint $table) {
                 $table->engine = 'InnoDB';
                 $table->increments('id');
                 $table->text('body');
                 $table->integer('sender_id')->unsigned()->index();
                 $table->foreign('sender_id')->references('id')->on('users');
                 $table->integer('receiver_id')->unsigned()->index();
                 $table->foreign('receiver_id')->references('id')->on('users');
                 $table->integer('chat_id')->unsigned()->index();
                 $table->foreign('chat_id')->references('id')->on('chats');
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
         Schema::dropIfExists('messages');
     }
}
