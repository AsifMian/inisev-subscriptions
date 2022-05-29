<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('sub_id');
            $table->integer('u_id')->unsigned();
            $table->integer('web_id')->unsigned();
            $table->tinyInteger('is_active')->default(1);
            $table->foreign('u_id')->references('u_id')->on('users');
            $table->foreign('web_id')->references('web_id')->on('websites');
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
        Schema::dropIfExists('subscriptions');
    }
}
