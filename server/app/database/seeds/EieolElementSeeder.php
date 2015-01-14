<?php

class EieolElementSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_element')->delete();

        EieolElement::create(array('gloss_id' => 1, 
        						 'part_of_speech' => 'conjunction',
        						 'analysis' => '',
         						 'head_word_id' => 1,  
        						 'order' => 10,
        						 'created_by' => 'fmcgrath', 
        						 'updated_by' => 'fmcgrath'));    
        EieolElement::create(array('gloss_id' => 2,
        		'part_of_speech' => 'adjective',
        		'analysis' => 'nominative plural feminine of',
        		'head_word_id' => 2,
        		'order' => 20,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolElement::create(array('gloss_id' => 3,
        		'part_of_speech' => 'noun, feminine',
        		'analysis' => 'nominative plural of',
        		'head_word_id' => 3,
        		'order' => 30,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolElement::create(array('gloss_id' => 4,
        		'part_of_speech' => 'verb',
        		'analysis' => '3rd person plural pluperfect subjunctive of',
        		'head_word_id' => 4,
        		'order' => 40,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
	}

}