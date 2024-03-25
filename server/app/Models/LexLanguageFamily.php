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
use App\Models\LexLanguageSubFamily;

/**
 * App\Models\LexLanguageFamily
 *
 * @property int $id
 * @property string|null $name
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $family
 * @property-read Collection|\App\Models\LexLanguageSubFamily[] $language_sub_families
 * @property-read int|null $language_sub_families_count
 * @method static Builder|LexLanguageFamily newModelQuery()
 * @method static Builder|LexLanguageFamily newQuery()
 * @method static Builder|LexLanguageFamily query()
 * @method static Builder|LexLanguageFamily whereCreatedAt($value)
 * @method static Builder|LexLanguageFamily whereCreatedBy($value)
 * @method static Builder|LexLanguageFamily whereId($value)
 * @method static Builder|LexLanguageFamily whereName($value)
 * @method static Builder|LexLanguageFamily whereOrder($value)
 * @method static Builder|LexLanguageFamily whereUpdatedAt($value)
 * @method static Builder|LexLanguageFamily whereUpdatedBy($value)
 * @property-read \App\Models\LexLexicon|null $lexicon
 * @property-read mixed $translations
 * @method static Builder|LexLanguageFamily whereLocale(string $column, string $locale)
 * @method static Builder|LexLanguageFamily whereLocales(string $column, array $locales)
 * @mixin Eloquent
 */
class LexLanguageFamily extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_language_family';

	protected $guarded = ['id'];

    protected $translatable = ['name'];

	public function language_sub_families()
	{
		return $this->hasMany(LexLanguageSubFamily::class, 'family_id', 'id')
            ->orderBy('order');
	}

	public function getFamilyAttribute()
	{
		return strip_tags($this->name);
	}

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }
}
