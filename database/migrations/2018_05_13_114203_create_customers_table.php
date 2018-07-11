<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
 	{
 		Schema::create('customers', function(Blueprint $table)
 		{
 			$table->increments('id');
 			$table->string('first_name', 100);
            $table->string('last_name',100);
 			$table->string('email', 30);
 			$table->string('phone', 20)->nullable();
 			$table->string('avatar', 255)->default('no-foto.png');
 			$table->string('address', 255)->nullable();
 			$table->string('city', 20)->nullable();
 			$table->string('state', 30)->nullable();
 			$table->string('zip', 10)->nullable();
            $table->string('country',50)->nullable();
 			$table->string('comment', 255)->nullable();
 			$table->string('company_name', 100)->nullable();
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
 		Schema::drop('customers');
 	}
}
