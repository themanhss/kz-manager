<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogspotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogspots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url',100);
            $table->integer('gmail_id');
            $table->string('blog_id',20);
            $table->text('description')->nullable();
            $table->date('start_at')->nullable();

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
        Schema::drop('blogspots');
    }
}
