<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('paymentid')->unique();
            $table->string('msisdn')->nullable();
            $table->string('countrycode')->nullable();
            $table->string('network')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('amount', 8, 2)->default(0);
            $table->string('description')->nullable();
            $table->bigInteger('duration')->default(0);
            $table->string('paymentref')->nullable();
            $table->string('externalreferenceno')->nullable();
            $table->string('message')->nullable();
            $table->string('status')->nullable();
            $table->date('expirydate')->nullable();
            $table->string('payerid')->nullable();

            $table->foreign('payerid')->references('userid')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('payments');
    }
}
