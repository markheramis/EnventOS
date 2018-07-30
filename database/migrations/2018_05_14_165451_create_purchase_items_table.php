<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items',function(Blueprint $table){
            $table->increments('id');
            $table->integer('purchase_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->decimal('cost_price',9,2);
            $table->integer('quantity');
            $table->decimal('total_cost',9,2);
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('restrict');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('purchase_items');
    }
}
