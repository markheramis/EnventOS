<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('product_code',90); // upc_ean_isbn
			$table->string('product_name',90);
			$table->string('size',20);
			$table->text('description');
			$table->string('avatar', 255)->default('no-foto.png');
			$table->decimal('cost_price',9, 2);
			$table->decimal('selling_price',9, 2);
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
