<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('name',255)->nullable();
            $table->string('status',45)->nullable();
            $table->integer('promotion_id')->nullable();
            $table->string('price')->nullable();
            $table->text('description')->nullable();

            $table->index('promotion_id','product_idx');
            $table->text('fields_json')->nullable();

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
        Schema::drop('product');
    }
}
