<?php

class EieolHeadWordSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_head_word')->delete();

        EieolHeadWord::create(array('word' => '<cum>', 
        						  'definition' => 'since, when',  
        						  'created_by' => 'fmcgrath', 
        						  'updated_by' => 'fmcgrath'));      
        EieolHeadWord::create(array('word' => '<instructus, instructa, instructum>', 
        						  'definition' => 'arranged, ready',  
        						  'created_by' => 'fmcgrath', 
        						  'updated_by' => 'fmcgrath'));      		  
		EieolHeadWord::create(array('word' => '<acies, aciei>', 
        						  'definition' => 'line of battle',  
        						  'created_by' => 'fmcgrath', 
        						  'updated_by' => 'fmcgrath'));      
        EieolHeadWord::create(array('word' => '<cōnstituō, cōnstituere, cōnstituī, cōnstitūtum>', 
        						  'definition' => 'decide',  
        						  'created_by' => 'fmcgrath', 
        						  'updated_by' => 'fmcgrath'));      						  
	}

}
