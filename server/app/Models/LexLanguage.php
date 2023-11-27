<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LexLanguageSubFamily;
use App\Models\LexReflex;

/**
 * App\Models\LexLanguage
 *
 * @property int $id
 * @property string|null $name
 * @property int $order
 * @property string|null $abbr
 * @property string|null $aka
 * @property int $sub_family_id
 * @property string|null $override_family
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read mixed $stripped_name
 * @property-read \App\Models\LexLanguageSubFamily $language_sub_family
 * @property-read Collection|\App\Models\LexReflex[] $reflex_count
 * @property-read int|null $reflex_count_count
 * @property-read Collection|\App\Models\LexReflex[] $reflexes
 * @property-read int|null $reflexes_count
 * @property-read Collection|\App\Models\LexReflex[] $small_reflexes
 * @property-read int|null $small_reflexes_count
 * @method static Builder|LexLanguage newModelQuery()
 * @method static Builder|LexLanguage newQuery()
 * @method static Builder|LexLanguage query()
 * @method static Builder|LexLanguage whereAbbr($value)
 * @method static Builder|LexLanguage whereAka($value)
 * @method static Builder|LexLanguage whereCreatedAt($value)
 * @method static Builder|LexLanguage whereCreatedBy($value)
 * @method static Builder|LexLanguage whereCustomSort($value)
 * @method static Builder|LexLanguage whereId($value)
 * @method static Builder|LexLanguage whereName($value)
 * @method static Builder|LexLanguage whereOrder($value)
 * @method static Builder|LexLanguage whereOverrideFamily($value)
 * @method static Builder|LexLanguage whereSubFamilyId($value)
 * @method static Builder|LexLanguage whereUpdatedAt($value)
 * @method static Builder|LexLanguage whereUpdatedBy($value)
 * @mixin Eloquent
 */
class LexLanguage extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_language';

	protected $guarded = ['id'];

    protected $translatable = ['name', 'description'];

	public static function boot() {
		parent::boot();

		// event to happen on saving
		static::creating(function($table)  {
            if (Auth::user()) {
                $table->created_by = Auth::user()->username;
                $table->updated_by = Auth::user()->username;
            }
		});

		// event to happen on updating
		static::updating(function($table)  {
            if (Auth::user()) {
                $table->updated_by = Auth::user()->username;
            }
		});
	}

	public function language_sub_family()
	{
		return $this->belongsTo(LexLanguageSubFamily::class, 'sub_family_id');
	}

	public function reflexes()
	{
		return $this->hasMany(LexReflex::class, 'language_id', 'id')
            ->orderBy('entries');
	}

	public function small_reflexes()
	{
		return $this->hasMany(LexReflex::class, 'language_id', 'id');
	}

	public function reflex_count()
	{
		return $this->hasMany(LexReflex::class, 'language_id', 'id')->select(DB::raw('language_id, count(*) as count'))->groupBy('language_id');
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
