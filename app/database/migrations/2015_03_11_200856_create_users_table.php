<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function ($table) {
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('firstname');
			$table->string('lastname');	
			$table->integer('locker');
			$table->string('state');
			$table->string('country');
			$table->string('school');
			$table->string('major');
			$table->decimal('lat', 10, 8)->nullable();
			$table->decimal('lng', 11, 8)->nullable();
			$table->integer('milesfromhome');
			$table->text('description');
			$table->text('image');
			$table->string('prefix');
			$table->boolean('studyabroad');
			$table->boolean('firstrun')->default(0);
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
		//
	}

}
