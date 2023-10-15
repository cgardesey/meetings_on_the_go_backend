<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('groupattendanceid')->unique();
            $table->string('userid')->nullable();
            $table->string('groupsessionid')->nullable();

            $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
            $table->foreign('groupsessionid')->references('groupsessionid')->on('group_sessions')->onDelete('cascade');

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
        Schema::dropIfExists('group_attendances');
    }
}
