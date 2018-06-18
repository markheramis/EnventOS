<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('customer_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned();

            $table->decimal('cost_price',9,2);
            $table->decimal('selling_price',9,2);
            $table->decimal('payment_amount',9,2);
			$table->enum('payment_type', ['Cash','Check','Debit','Credit'])->default('Cash')->nullable();
            $table->enum('status',['complete','delivering','processing','cancelled'])->default('processing')->nullable();
			$table->string('comments', 255)->default('N/A')->nullable();
			$table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
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
        Schema::drop('sales');
    }
}
