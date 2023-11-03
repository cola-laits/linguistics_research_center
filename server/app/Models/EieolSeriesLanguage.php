<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|EieolSeriesLanguage newModelQuery()
 * @method static Builder|EieolSeriesLanguage newQuery()
 * @method static Builder|EieolSeriesLanguage query()
 * @method static Builder|EieolSeriesLanguage whereDisplay($value)
 * @method static Builder|EieolSeriesLanguage whereId($value)
 * @method static Builder|EieolSeriesLanguage whereLang($value)
 * @method static Builder|EieolSeriesLanguage whereSeriesId($value)
 * @mixin Eloquent
 */
class EieolSeriesLanguage extends Model {

	protected $table = 'eieol_series_language';

  public $timestamps = false;

	public function series()
	{
		return $this->belongsTo(EieolSeries::class);
	}

}
