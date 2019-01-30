<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EieolSeries extends Model {
	
	protected $table = 'eieol_series';
	
	public function lessons()
	{
		return $this->hasMany('\App\EieolLesson', 'series_id', 'id')->orderBy('order');
	}
	
	public function languages()
	{
		return $this->hasMany('\App\EieolSeriesLanguage', 'series_id', 'id')->orderBy('display');
	}
	
}
