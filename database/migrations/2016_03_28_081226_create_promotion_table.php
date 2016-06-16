<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->increments('promotion_id');
            $table->string('code',45)->nullable();
            $table->string('name',255)->nullable();
            $table->integer('theme_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status',45)->nullable();
            $table->text('description')->nullable();
            $table->text('fields_json')->nullable();

            $table->foreign('theme_id')->references('theme_id')->on('theme');
            $table->index('theme_id', 'theme_idx');

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
        Schema::drop('promotion');
    }
}
