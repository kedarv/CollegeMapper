<?php

use \Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder {
    public function run() {
   		$data = array(
   			['id' => 1, 'name' => 'Engineering'],
   			['id' => 2, 'name' => 'Arts and Sciences'],
   			['id' => 3, 'name' => 'Business'],
   			['id' => 4, 'name' => 'No Category'],
        );
        DB::table('category')->insert($data);
	}
}