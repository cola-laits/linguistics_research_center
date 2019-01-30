<?php 

class EieolSeries extends Eloquent {
	
	protected $table = 'eieol_series';
	
	public function lessons()
	{
		return $this->hasMany('EieolLesson', 'series_id', 'id')->orderBy('order');
	}
	
	public function languages()
	{
		return $this->hasMany('EieolSeriesLanguage', 'series_id', 'id')->orderBy('display');
	}
	
}