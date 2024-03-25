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

/**
 * App\Models\LexSemanticCategory
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $number
 * @property string|null $abbr
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|LexSemanticField[] $semantic_fields
 * @property-read int|null $semantic_fields_count
 * @method static Builder|LexSemanticCategory newModelQuery()
 * @method static Builder|LexSemanticCategory newQuery()
 * @method static Builder|LexSemanticCategory query()
 * @method static Builder|LexSemanticCategory whereAbbr($value)
 * @method static Builder|LexSemanticCategory whereCreatedAt($value)
 * @method static Builder|LexSemanticCategory whereCreatedBy($value)
 * @method static Builder|LexSemanticCategory whereId($value)
 * @method static Builder|LexSemanticCategory whereNumber($value)
 * @method static Builder|LexSemanticCategory whereText($value)
 * @method static Builder|LexSemanticCategory whereUpdatedAt($value)
 * @method static Builder|LexSemanticCategory whereUpdatedBy($value)
 * @property-read mixed $lex_text
 * @property-read \App\Models\LexLexicon|null $lexicon
 * @property-read mixed $translations
 * @method static Builder|LexSemanticCategory whereLocale(string $column, string $locale)
 * @method static Builder|LexSemanticCategory whereLocales(string $column, array $locales)
 * @mixin Eloquent
 */
class LexSemanticCategory extends Model
{

    use CrudTrait;
    use HasTranslations;

    protected $table = 'lex_semantic_category';

    protected $fillable = ['number', 'text', 'abbr'];

    protected $translatable = ['text'];

    public function semantic_fields()
    {
        return $this->hasMany(LexSemanticField::class, 'semantic_category_id', 'id')->orderBy('number');
    }

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }

    public function getLexTextAttribute()
    {
        return $this->lexicon->name . ' - ' . $this->text;
    }
}
