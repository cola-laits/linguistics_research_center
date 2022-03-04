<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EieolSeries;

/**
 * App\Models\EieolSeriesLanguage
 *
 * @property int $id
 * @property int $series_id
 * @property string $lang
 * @property string $display
 * @property-read \App\Models\EieolSeries $series
 * @method static \Illuminate\Database\Eloquent\Builder|EieolSeriesLanguage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolSeriesLanguage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolSeriesLanguage query()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolSeriesLanguage whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolSeriesLanguage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolSeriesLanguage whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolSeriesLanguage whereSeriesId($value)
 * @mixin \Eloquent
 */
class EieolSeriesLanguage extends Model {

	protected $table = 'eieol_series_language';

  public $timestamps = false;

	public function series()
	{
		return $this->belongsTo(EieolSeries::class);
	}

}
