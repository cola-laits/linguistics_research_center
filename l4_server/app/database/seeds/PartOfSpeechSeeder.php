<?php

class PartOfSpeechSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('part_of_speech')->delete();

        PartOfSpeech::create(array('part_of_speech' => 'noun', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));      
        PartOfSpeech::create(array('part_of_speech' => 'verb', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        PartOfSpeech::create(array('part_of_speech' => 'pronoun', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        PartOfSpeech::create(array('part_of_speech' => 'adjective', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        PartOfSpeech::create(array('part_of_speech' => 'adverb', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
                
	}

}
