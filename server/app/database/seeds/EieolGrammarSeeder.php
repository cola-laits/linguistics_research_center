<?php

class EieolGrammarSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_grammar')->delete();

        EieolGrammar::create(array('title' => 'Latin, a Subject-Object-Verb (SOV) Language.', 
        						  'lesson_id' => 1, 
        						  'order' => 0,
        						  'grammar_text' => 'this is the content of the grammar lesson', 
        						  'section_number' => '1', 
        						  'created_by' => 'fmcgrath', 
        						  'updated_by' => 'fmcgrath'));      

        EieolGrammar::create(array('title' => 'Modifications of the basic sentence pattern, with non-finite forms making up the verbs in clauses.',
        		'lesson_id' => 1,
        		'order' => 10,
        		'grammar_text' => 'this is the content of the second grammar lesson',
        		'section_number' => '2',
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'));
          

	}

}
