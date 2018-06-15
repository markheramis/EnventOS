<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivings',function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('payment_type',15)->nullable();
            $table->decimal('cost_price',9,2);
            $table->decimal('selling_price',9,2);
            $table->decimal('amount_tendered',9,2);
            $table->string('comments',255)->default('N/A')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('receivings');
    }
}
