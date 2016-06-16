<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_field', function (Blueprint $table) {
            $table->increments('theme_field_promo_id');
            $table->integer('theme_id')->nullable();
            $table->integer('order')->nullable();
            $table->string('field_name',255)->nullable();
            $table->string('field_type',45)->nullable();
            $table->string('field_label',255)->nullable();
            $table->string('field_help_image',2000)->nullable();
            $table->string('promo_or_product',45)->nullable();

            $table->index('theme_id','theme_id_idx');
            $table->foreign('theme_id')->references('theme_id')->on('theme');

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
        Schema::drop('theme_field');
    }
}
