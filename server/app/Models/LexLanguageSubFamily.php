<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LexLanguageFamily;
use App\Models\LexLanguage;

/**
 * App\Models\LexLanguageSubFamily
 *
 * @property int $id
 * @property string|null $name
 * @property int $order
 * @property int $family_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $family_sub_family
 * @property-read \App\Models\LexLanguageFamily $language_family
 * @property-read Collection|\App\Models\LexLanguage[] $languages
 * @property-read int|null $languages_count
 * @method static Builder|LexLanguageSubFamily newModelQuery()
 * @method static Builder|LexLanguageSubFamily newQuery()
 * @method static Builder|LexLanguageSubFamily query()
 * @method static Builder|LexLanguageSubFamily whereCreatedAt($value)
 * @method static Builder|LexLanguageSubFamily whereCreatedBy($value)
 * @method static Builder|LexLanguageSubFamily whereFamilyId($value)
 * @method static Builder|LexLanguageSubFamily whereId($value)
 * @method static Builder|LexLanguageSubFamily whereName($value)
 * @method static Builder|LexLanguageSubFamily whereOrder($value)
 * @method static Builder|LexLanguageSubFamily whereUpdatedAt($value)
 * @method static Builder|LexLanguageSubFamily whereUpdatedBy($value)
 * @property-read mixed $translations
 * @method static Builder|LexLanguageSubFamily whereLocale(string $column, string $locale)
 * @method static Builder|LexLanguageSubFamily whereLocales(string $column, array $locales)
 * @mixin Eloquent
 */
class LexLanguageSubFamily extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_language_sub_family';

	protected $guarded = ['id'];

    protected $translatable = ['name'];

	public function languages()
	{
		return $this->hasMany(LexLanguage::class, 'sub_family_id', 'id')
            ->orderBy('order');
	}

	public function language_family()
	{
		return $this->belongsTo(LexLanguageFamily::class, 'family_id');
	}

	public function getFamilySubFamilyAttribute()
	{
		return strip_tags($this->language_family()->first()->name) . '->' . strip_tags($this->name);
	}

}
