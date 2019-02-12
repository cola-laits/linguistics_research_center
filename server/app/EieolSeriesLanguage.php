<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolSeriesLanguage
 *
 * @property int $id
 * @property int $series_id
 * @property string $lang
 * @property string $display
 * @property-read \App\EieolSeries $series
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeriesLanguage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeriesLanguage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeriesLanguage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeriesLanguage whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeriesLanguage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeriesLanguage whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeriesLanguage whereSeriesId($value)
 * @mixin \Eloquent
 */
class EieolSeriesLanguage extends Model {

	protected $table = 'eieol_series_language';
  
  public $timestamps = false;

	public function series()
	{
		return $this->belongsTo('\App\EieolSeries');
	}
	
}
