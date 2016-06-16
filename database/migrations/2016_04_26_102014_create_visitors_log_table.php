<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorsTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors_log', function (Blueprint $table) {
            $table->increments('visitors_log_id');
            $table->string('visitor_email',255)->nullable();
            $table->string('visitor_ip',32)->nullable();
            $table->text('visitor_browser')->nullable();
            $table->dateTime('visitor_date');
            $table->string('visitor_referral',255)->nullable();
            $table->string('visitor_page',255)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('visitors_table');
    }
}
