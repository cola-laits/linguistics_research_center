<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolSeries
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $slug
 * @property int $order
 * @property string|null $menu_name
 * @property string|null $menu_order
 * @property string|null $expanded_title
 * @property int|null $published
 * @property int|null $use_old_gloss_ui
 * @property string|null $meta_tags
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolSeriesLanguage[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolLesson[] $lessons
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereExpandedTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereMenuName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereMenuOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereMetaTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolSeries whereUseOldGlossUi($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolLanguage[] $lesson_languages
 */
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

	public function lesson_languages() {
	    return $this->belongsToMany('\App\EieolLanguage','eieol_lesson', 'series_id', 'language_id')
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
	    return \App\EieolLesson::where('series_id', $this->id)
            ->where('title', 'like', '%Bibliography%')
            ->first();
    }
}
