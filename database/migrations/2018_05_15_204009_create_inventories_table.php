<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('item_id')->unsigned();
			$table->integer('user_id')->unsigned();
            $table->integer('sales_id')->unsigned()->nullable();
            $table->integer('receiving_id')->unsigned()->nullable();
			$table->integer('in_out_qty');
			$table->string('remarks', 255);
			$table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('sales_id')->references('id')->on('sales')->onDelete('restrict');
            $table->foreign('receiving_id')->references('id')->on('receivings')->onDelete('restrict');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inventories');
    }
}
