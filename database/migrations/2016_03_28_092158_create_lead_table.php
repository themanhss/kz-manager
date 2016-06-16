<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead', function (Blueprint $table) {
            $table->increments('lead_id');
            $table->integer('client_id');
            $table->integer('promotion_id');
            $table->string('product_ids',45)->nullable();
            $table->date('date')->nullable();
            $table->enum('source_type', ['Website Enquiry', 'Live Chat', 'Phone'])->nullable();
            $table->text('message')->nullable();
            $table->string('phone',45)->nullable();
            $table->string('email',255)->nullable();

            $table->index('client_id','client_idx');
            $table->index('promotion_id','promotion_lead_idx');

            $table->foreign('client_id')->references('id')->on('clients');
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
        Schema::drop('lead');
    }
}
