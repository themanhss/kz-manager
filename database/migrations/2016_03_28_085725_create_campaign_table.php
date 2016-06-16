<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign', function (Blueprint $table) {
            $table->increments('campaign_id');
            $table->string('name',255)->nullable();
            $table->string('type',45)->nullable();
            $table->date('send_date')->nullable();
            $table->string('sms_text',255)->nullable();
            $table->integer('promotion_id')->nullable();
            $table->index('promotion_id','promotion_idx');

            $table->foreign('promotion_id')->references('promotion_id')->on('promotion');

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
        Schema::drop('campaign');
    }
}
