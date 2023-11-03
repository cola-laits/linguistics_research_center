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

/**
 * App\Models\EieolSeries
 *
 * @property int $id
 * @property string|null $title
 * @property string $slug
 * @property int $order
 * @property string|null $menu_name
 * @property string|null $menu_order
 * @property string|null $expanded_title
 * @property int|null $published
 * @property string|null $meta_tags
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read Collection|\App\Models\EieolSeriesLanguage[] $languages
 * @property-read int|null $languages_count
 * @property-read Collection|\App\Models\EieolLanguage[] $lesson_languages
 * @property-read int|null $lesson_languages_count
 * @property-read Collection|\App\Models\EieolLesson[] $lessons
 * @property-read int|null $lessons_count
 * @method static Builder|EieolSeries newModelQuery()
 * @method static Builder|EieolSeries newQuery()
 * @method static Builder|EieolSeries query()
 * @method static Builder|EieolSeries whereCreatedAt($value)
 * @method static Builder|EieolSeries whereCreatedBy($value)
 * @method static Builder|EieolSeries whereExpandedTitle($value)
 * @method static Builder|EieolSeries whereId($value)
 * @method static Builder|EieolSeries whereMenuName($value)
 * @method static Builder|EieolSeries whereMenuOrder($value)
 * @method static Builder|EieolSeries whereMetaTags($value)
 * @method static Builder|EieolSeries whereOrder($value)
 * @method static Builder|EieolSeries wherePublished($value)
 * @method static Builder|EieolSeries whereSlug($value)
 * @method static Builder|EieolSeries whereTitle($value)
 * @method static Builder|EieolSeries whereUpdatedAt($value)
 * @method static Builder|EieolSeries whereUpdatedBy($value)
 * @mixin Eloquent
 */
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
