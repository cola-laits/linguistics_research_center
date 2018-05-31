<?php 

class EieolSeriesLanguage extends Eloquent {

	protected $table = 'eieol_series_language';
  
  public $timestamps = false;

	public function series()
	{
		return $this->belongsTo('EieolSeries');
	}
	
}