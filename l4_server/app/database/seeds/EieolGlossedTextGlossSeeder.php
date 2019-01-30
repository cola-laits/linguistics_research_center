<?php

class EieolGlossedTextGlossSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_glossed_text_gloss')->delete();

        EieolGlossedTextGloss::create(array('glossed_text_id' => 1, 
        						  'gloss_id' => 1,  
        						  'order' => 10,
        						  'created_by' => 'fmcgrath', 
        						  'updated_by' => 'fmcgrath'));  
        EieolGlossedTextGloss::create(array('glossed_text_id' => 1,
        		'gloss_id' => 2,
        		'order' => 20,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolGlossedTextGloss::create(array('glossed_text_id' => 1,
        		'gloss_id' => 3,
        		'order' => 30,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolGlossedTextGloss::create(array('glossed_text_id' => 1,
        		'gloss_id' => 4,
        		'order' => 40,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
	}

}
