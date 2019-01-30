<?php

class EieolAnalysisSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_analysis')->delete();

        EieolAnalysis::create(array('analysis' => 'nominative plural feminine of', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));      
        EieolAnalysis::create(array('analysis' => 'nominative plural of', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolAnalysis::create(array('analysis' => '3rd person plural pluperfect subjunctive of', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolAnalysis::create(array('analysis' => '3rd person plural imperfect subjunctive of', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolAnalysis::create(array('analysis' => 'perfect infinitive of', 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
                
	}

}
