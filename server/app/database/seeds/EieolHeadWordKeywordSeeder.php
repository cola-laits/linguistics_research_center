<?php

class EieolHeadWordKeywordSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_head_word_keyword')->delete();

        EieolHeadWordKeyword::create(array('keyword' => 'since', 
        						  'head_word_id' => 1,  
        						  'created_by' => 'fmcgrath', 
        						  'updated_by' => 'fmcgrath'));    
        EieolHeadWordKeyword::create(array('keyword' => 'when',
        		'head_word_id' => 1,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'arranged',
        		'head_word_id' => 2,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'ready',
        		'head_word_id' => 2,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'line',
        		'head_word_id' => 3,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'battle',
        		'head_word_id' => 3,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'decide',
        		'head_word_id' => 4,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
	}

}
