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
        						 'contextual_gloss' => 'when',
        						 'language_id' => 1,
        						 'created_by' => 'fmcgrath', 
        						 'updated_by' => 'fmcgrath'));    
        EieolGloss::create(array('surface_form' => 'instructae',
        		'contextual_gloss' => 'ready',
        		'language_id' => 1,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolGloss::create(array('surface_form' => 'acies',
        		'contextual_gloss' => 'lines of battle',
        		'language_id' => 1,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolGloss::create(array('surface_form' => 'constitissent',
        		'contextual_gloss' => 'had drawn up',
        		'language_id' => 1,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
	}

}
