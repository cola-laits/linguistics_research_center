<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\EieolSeries;

class EieolSeriesLanguage extends Model {

	protected $table = 'eieol_series_language';

  public $timestamps = false;

	public function series()
	{
		return $this->belongsTo(EieolSeries::class);
	}

}
