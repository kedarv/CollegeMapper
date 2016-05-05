<?php

use \Illuminate\Database\Seeder;

class MajorTableSeeder extends Seeder {
    public function run() {
   		$data = array(
   			['id' => 1, 'name' => 'General Engineering', 'category' => 1],
   			['id' => 2, 'name' => 'Chemical/Bio Engineering', 'category' => 1],
   			['id' => 3, 'name' => 'Biomedical Engineering', 'category' => 1],
   			['id' => 4, 'name' => 'Civil Engineering', 'category' => 1],
   			['id' => 5, 'name' => 'Environmental Engineering', 'category' => 1],
   			['id' => 6, 'name' => 'Electrical/Computer Engineering', 'category' => 1],
   			['id' => 7, 'name' => 'Computer Science', 'category' => 1],
   			['id' => 8, 'name' => 'Mechanical Engineering', 'category' => 1],
   			['id' => 9, 'name' => 'Aerospace Engineering', 'category' => 1],
   			['id' => 10, 'name' => 'General/Undecided Engineering', 'category' => 1],
   			['id' => 11, 'name' => 'Child Psychology', 'category' => 2],
			['id' => 12, 'name' => 'Linguistics', 'category' => 2],
			['id' => 13, 'name' => 'Kinesiology', 'category' => 2],
			['id' => 14, 'name' => 'Biology', 'category' => 2],
			['id' => 15, 'name' => 'Chemistry', 'category' => 2],
			['id' => 16, 'name' => 'Biochemistry', 'category' => 2],
			['id' => 17, 'name' => 'Communication', 'category' => 2],
			['id' => 18, 'name' => 'Foreign Language', 'category' => 2],
			['id' => 19, 'name' => 'Anthropology', 'category' => 2],
			['id' => 20, 'name' => 'Psychology', 'category' => 2],
			['id' => 21, 'name' => 'Sociology', 'category' => 2],
			['id' => 22, 'name' => 'Political Science', 'category' => 2],
			['id' => 23, 'name' => 'International Studies', 'category' => 2],
			['id' => 24, 'name' => 'Philosophy', 'category' => 2],
			['id' => 25, 'name' => 'Architecture', 'category' => 2],
			['id' => 26, 'name' => 'Agriculture and Consumer Economics', 'category' => 2],
			['id' => 27, 'name' => 'English', 'category' => 2],
			['id' => 28, 'name' => 'History', 'category' => 2],
			['id' => 29, 'name' => 'Music', 'category' => 2],
			['id' => 30, 'name' => 'Music Cognition', 'category' => 2],
			['id' => 31, 'name' => 'Theatre', 'category' => 2],
			['id' => 32, 'name' => 'Zoology', 'category' => 2],
			['id' => 33, 'name' => 'Business', 'category' => 3],
			['id' => 34, 'name' => 'International Business', 'category' => 3],
			['id' => 35, 'name' => 'Economics', 'category' => 3],
			['id' => 36, 'name' => 'Education', 'category' => 4],
			['id' => 37, 'name' => 'Law', 'category' => 4],
			['id' => 38, 'name' => 'Media', 'category' => 4],
			['id' => 39, 'name' => 'Medicine', 'category' => 4],
			['id' => 40, 'name' => 'Public Health', 'category' => 4],
			['id' => 41, 'name' => 'General/Other', 'category' => 4],
			['id' => 42, 'name' => 'Gap Year', 'category' => 4],
			['id' => 43, 'name' => 'Undecided', 'category' => 4],
        );
        DB::table('majors')->insert($data);
	}
}