<?php

class EieolGlossedTextSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_glossed_text')->delete();

        EieolGlossedText::create(array('lesson_id' => 1, 
        						  'order' => 0,
        						  'glossed_text' => 'Cum instructae acies constitissent, priusquam signa canerent, processisse Latinum inter primores ducemque advenarum evocasse ad conloquium.',  
        						  'created_by' => 'fmcgrath', 
        						  'updated_by' => 'fmcgrath'));      
        
        EieolGlossedText::create(array('lesson_id' => 1,
        		'order' => 10,
        		'glossed_text' => 'Percunctatum deinde qui mortales essent, unde aut quo casu profecti domo quidve quaerentes in agrum Laurentinum exissent.',
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));

	}

}
