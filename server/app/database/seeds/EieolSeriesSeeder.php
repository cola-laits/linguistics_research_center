<?php

class EieolSeriesSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		DB::table('eieol_series')->delete();

        EieolSeries::create(array('title' => 'Latin Online', 'order' => 10, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Latin', 'expanded_title' => 'Latin Online', 'menu_order' => 120));        
        EieolSeries::create(array('title' => 'Classical Greek Online', 'order' => 20, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Cl. Greek', 'expanded_title' => 'Classical Greek Online (Attic)', 'menu_order' => 70));
        EieolSeries::create(array('title' => 'New Testament Greek Online', 'order' => 30, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'N. T. Greek', 'expanded_title' => 'New Testament Greek Online (Koine)', 'menu_order' => 80));
        EieolSeries::create(array('title' => 'Old Church Slavonic Online', 'order' => 40, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Old Slavonic', 'expanded_title' => 'Old Church Slavonic Online (Old Church Slavic)', 'menu_order' => 170));
        EieolSeries::create(array('title' => 'Classical Armenian Online', 'order' => 50, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Armenian', 'expanded_title' => 'Classical Armenian Online', 'menu_order' => 20));
        EieolSeries::create(array('title' => 'Old Iranian Online', 'order' => 60, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Old Iranian', 'expanded_title' => 'Old Iranian Online (Avestan &amp; Old Persian)', 'menu_order' => 100));
        EieolSeries::create(array('title' => 'Old Norse Online', 'order' => 70, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Old Norse', 'expanded_title' => 'Old Norse Online (Old Icelandic)', 'menu_order' => 130));
        EieolSeries::create(array('title' => 'Baltic Online', 'order' => 80, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Baltic', 'expanded_title' => 'Baltic Online (Lithuanian &amp; Latvian)', 'menu_order' => 30));
        EieolSeries::create(array('title' => 'Hittite Online', 'order' => 90, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Hittite', 'expanded_title' => 'Hittite Online', 'menu_order' => 90));
        EieolSeries::create(array('title' => 'Ancient Sanskrit Online', 'order' => 100, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Sanskrit', 'expanded_title' => 'Ancient Sanskrit Online (Rigvedic)', 'menu_order' => 150));
        EieolSeries::create(array('title' => 'Gothic Online', 'order' => 110, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Gothic', 'expanded_title' => 'Gothic Online', 'menu_order' => 60));
        EieolSeries::create(array('title' => 'Old French Online', 'order' => 120, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Old French', 'expanded_title' => 'Old French Online', 'menu_order' => 50));
        EieolSeries::create(array('title' => 'Old Irish Online', 'order' => 130, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Old Irish', 'expanded_title' => 'Old Irish Online', 'menu_order' => 110));
        EieolSeries::create(array('title' => 'Old English Online', 'order' => 140, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Old English', 'expanded_title' => 'Old English Online', 'menu_order' => 40));
        EieolSeries::create(array('title' => 'Tocharian Online', 'order' => 150, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Tocharian', 'expanded_title' => 'Tocharian Online (A: Turfanian &amp; B: Kuchean)', 'menu_order' => 180));
        EieolSeries::create(array('title' => 'Albanian Online', 'order' => 160, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Albanian', 'expanded_title' => 'Albanian Online (Tosk &amp; Geg)', 'menu_order' => 10));
        EieolSeries::create(array('title' => 'Old Russian Online', 'order' => 170, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath', 'menu_name' => 'Old Russian', 'expanded_title' => 'Old Russian Online (Old East Slavic)', 'menu_order' => 140));
        
	}

}
