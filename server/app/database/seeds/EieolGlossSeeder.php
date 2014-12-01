<?php

class EieolGlossSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_gloss')->delete();

        EieolGloss::create(array('surface_form' => 'cum', 
        						 'part_of_speech' => 'conjunction',
        						 'analysis' => '',
         						 'head_word_id' => 1,  
        						 'contextual_gloss' => 'when',
        						 'created_by' => 'fmcgrath', 
        						 'updated_by' => 'fmcgrath'));    
        EieolGloss::create(array('surface_form' => 'instructae',
        		'part_of_speech' => 'adjective',
        		'analysis' => 'nominative plural feminine of',
        		'head_word_id' => 2,
        		'contextual_gloss' => 'ready',
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolGloss::create(array('surface_form' => 'acies',
        		'part_of_speech' => 'noun, feminine',
        		'analysis' => 'nominative plural of',
        		'head_word_id' => 3,
        		'contextual_gloss' => 'lines of battle',
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolGloss::create(array('surface_form' => 'constitissent',
        		'part_of_speech' => 'verb',
        		'analysis' => '3rd person plural pluperfect subjunctive of',
        		'head_word_id' => 4,
        		'contextual_gloss' => 'had drawn up',
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
	}

}
