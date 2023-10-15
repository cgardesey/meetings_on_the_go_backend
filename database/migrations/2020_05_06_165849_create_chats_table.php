<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('chatid')->unique();
            $table->string('chatrefid')->nullable();
            $table->string('text')->nullable();
            $table->string('link')->nullable();
            $table->string('linktitle')->nullable();
            $table->string('linkdescription')->nullable();
            $table->string('linkimage')->nullable();
            $table->string('attachmenturl')->nullable();
            $table->string('attachmenttitle')->nullable();
            $table->boolean('readbyrecepient')->default(false);
            $table->string('groupid');
            $table->string('senderid');
            $table->string('recepientid')->nullable();

            $table->foreign('groupid')->references('groupid')->on('groups')->onDelete('cascade');
            $table->foreign('senderid')->references('userid')->on('users')->onDelete('cascade');
            $table->foreign('recepientid')->references('userid')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('chats');
    }
}
