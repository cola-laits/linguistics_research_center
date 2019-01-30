<?php

class EieolLanguageSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_language')->delete();

        EieolLanguage::create(array('language' => 'Latin', 
        						    'created_by' => 'fmcgrath', 
        						    'updated_by' => 'fmcgrath'));            						  
	}

}
