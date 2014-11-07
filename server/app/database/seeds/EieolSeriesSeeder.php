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

        EieolSeries::create(array('title' => 'Latin Online', 'order' => 10, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));        
        EieolSeries::create(array('title' => 'Classical Greek Online', 'order' => 20, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'New Testament Greek Online', 'order' => 30, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Old Church Slavonic Online', 'order' => 40, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Classical Armenian Online', 'order' => 50, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Old Iranian Online', 'order' => 60, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Old Norse Online', 'order' => 70, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Baltic Online', 'order' => 80, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Hittite Online', 'order' => 90, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Ancient Sanskrit Online', 'order' => 100, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Gothic Online', 'order' => 110, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Old French Online', 'order' => 120, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Old Irish Online', 'order' => 130, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Old English Online', 'order' => 140, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Tocharian Online', 'order' => 150, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Albanian Online', 'order' => 160, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        EieolSeries::create(array('title' => 'Old Russian Online', 'order' => 170, 'published' => True, 'created_by' => 'fmcgrath', 'updated_by' => 'fmcgrath'));
        
	}

}
