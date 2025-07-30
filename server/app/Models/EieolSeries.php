<?php

namespace App\Models;

use App\Models;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\EieolLesson;
use App\Models\EieolSeriesLanguage;
use App\Models\EieolLanguage;
use Illuminate\Support\Carbon;

class EieolSeries extends Model {

    use CrudTrait;

	protected $table = 'eieol_series';

    protected $guarded = ['id'];

	public function lessons()
	{
		return $this->hasMany(EieolLesson::class, 'series_id', 'id')->orderBy('order');
	}

	public function languages()
	{
		return $this->hasMany(EieolSeriesLanguage::class, 'series_id', 'id')->orderBy('display');
	}

	public function lesson_languages() {
	    return $this->belongsToMany(EieolLanguage::class, 'eieol_lesson', 'series_id', 'language_id')
            ->distinct('language_id');
    }

	public static function findByIdOrSlug($text) {
	    // series are referenced by slug, but there's still calls out there that reference them by DB PK.
        if (is_numeric($text)) {
            return self::findOrFail($text);
        }

        return self::where("slug", $text)->firstOrFail();
    }

    public function getBibliographyLesson() {
	    return Models\EieolLesson::where('series_id', $this->id)
            ->where('title', 'like', '%Bibliography%')
            ->first();
    }
}
