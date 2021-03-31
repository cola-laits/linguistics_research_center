<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexLanguage
 *
 * @property int $id
 * @property string|null $name
 * @property int $order
 * @property string|null $abbr
 * @property string|null $aka
 * @property int $sub_family_id
 * @property string|null $override_family
 * @property string|null $custom_sort
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read mixed $stripped_name
 * @property-read \App\LexLanguageSubFamily $language_sub_family
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexReflex[] $reflex_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexReflex[] $reflexes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexReflex[] $small_reflexes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereAka($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereCustomSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereOverrideFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereSubFamilyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguage whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexLanguage extends Model {

    use CrudTrait;

	protected $table = 'lex_language';

	protected $guarded = ['id'];

	public static function boot() {
		parent::boot();

		// event to happen on saving
		static::creating(function($table)  {
			$table->created_by = Auth::user()->username;
			$table->updated_by = Auth::user()->username;
		});

		// event to happen on updating
		static::updating(function($table)  {
			$table->updated_by = Auth::user()->username;
		});
	}

	public function language_sub_family()
	{
		return $this->belongsTo('\App\LexLanguageSubFamily','sub_family_id');
	}

	public function reflexes()
	{
		return $this->hasMany('\App\LexReflex', 'language_id', 'id');
	}

	public function small_reflexes()
	{
		return $this->hasMany('\App\LexReflex', 'language_id', 'id');
	}

	public function reflex_count()
	{
		return $this->hasMany('\App\LexReflex', 'language_id', 'id')->select(\DB::raw('language_id, count(*) as count'))->groupBy('language_id');
	}

	public function getStrippedNameAttribute()
	{
		return strip_tags($this->name);
	}

	public function displayFamily()
	{
		if ($this->override_family != '') {
			return $this->override_family;
		} else {
			return $this->language_sub_family->language_family->name;
		}
	}
}
