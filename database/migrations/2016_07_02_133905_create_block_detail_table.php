<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('block_id');
            $table->string('title',255);
            $table->string('content',255);
            $table->string('delete_item',255);

            $table->foreign('block_id')->references('id')->on('blocks');

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
        Schema::drop('block_detail');
    }
}
