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

        EieolHeadWordKeyword::create(array('keyword' => 'SINCE', 
        						  'head_word_id' => 1,  
        						  'created_by' => 'fmcgrath', 
        						  'updated_by' => 'fmcgrath'));    
        EieolHeadWordKeyword::create(array('keyword' => 'WHEN',
        		'head_word_id' => 1,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'ARRANGED',
        		'head_word_id' => 2,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'READY',
        		'head_word_id' => 2,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'LINE',
        		'head_word_id' => 3,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'BATTLE',
        		'head_word_id' => 3,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
        EieolHeadWordKeyword::create(array('keyword' => 'DECIDE',
        		'head_word_id' => 4,
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
	}

}
