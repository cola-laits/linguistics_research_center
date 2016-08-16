<?php

class EieolLessonSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_lesson')->delete();

        EieolLesson::create(array('title' => 'Latin Series Introduction', 'series_id' => 1, 'order' => 0, 'intro_text' => 'this is the Latin intro', 'lesson_translation' => 'Sunny day, sweeping the clouds away', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));        
        EieolLesson::create(array('title' => 'CL Greek Series Introduction', 'series_id' => 2, 'order' => 0, 'intro_text' => 'this is the Greek intro', 'lesson_translation' => 'Sunny day, sweeping the clouds away', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        

	}

}
