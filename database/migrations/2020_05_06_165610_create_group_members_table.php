<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('groupmemberid')->unique();
            $table->string('groupid')->nullable();
            $table->string('memberid')->nullable();
            $table->boolean('admin')->default(false);

            $table->foreign('groupid')->references('groupid')->on('groups')->onDelete('cascade');
            $table->foreign('memberid')->references('userid')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('group_members');
    }
}
