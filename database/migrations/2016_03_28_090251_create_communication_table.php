<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communication', function (Blueprint $table) {
            $table->increments('communication_id');
            $table->integer('client_id')->nullable();
            $table->date('date')->nullable();
            $table->text('message')->nullable();
            $table->integer('campaign_id')->nullable();;
            $table->string('phone',45)->nullable();;
            $table->string('email',255)->nullable();;

            $table->index('campaign_id','campaign_idx');
            $table->index('client_id','clients_idx');

            $table->foreign('campaign_id')->references('campaign_id')->on('campaign');
            $table->foreign('client_id')->references('id')->on('clients');

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
        Schema::drop('communication');
    }
}
