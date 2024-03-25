<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\LexEtyma
 *
 * @property int $id
 * @property string|null $old_id
 * @property int $order
 * @property string|null $page_number
 * @property string|null $entry
 * @property string|null $gloss
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|LexEtyma[] $cross_references
 * @property-read int|null $cross_references_count
 * @property-read Collection|LexReflex[] $reflexes
 * @property-read int|null $reflexes_count
 * @property-read Collection|LexSemanticField[] $semantic_fields
 * @property-read int|null $semantic_fields_count
 * @method static Builder|LexEtyma newModelQuery()
 * @method static Builder|LexEtyma newQuery()
 * @method static Builder|LexEtyma query()
 * @method static Builder|LexEtyma whereCreatedAt($value)
 * @method static Builder|LexEtyma whereCreatedBy($value)
 * @method static Builder|LexEtyma whereEntry($value)
 * @method static Builder|LexEtyma whereGloss($value)
 * @method static Builder|LexEtyma whereId($value)
 * @method static Builder|LexEtyma whereOldId($value)
 * @method static Builder|LexEtyma whereOrder($value)
 * @method static Builder|LexEtyma wherePageNumber($value)
 * @method static Builder|LexEtyma whereUpdatedAt($value)
 * @method static Builder|LexEtyma whereUpdatedBy($value)
 * @property-read Collection<int, \App\Models\LexEtymaExtraData> $extra_data
 * @property-read int|null $extra_data_count
 * @property-read \App\Models\LexLexicon|null $lexicon
 * @property-read mixed $translations
 * @method static Builder|LexEtyma whereLocale(string $column, string $locale)
 * @method static Builder|LexEtyma whereLocales(string $column, array $locales)
 * @mixin Eloquent
 */
class LexEtyma extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_etyma';
	protected $guarded = ['id'];
    protected $translatable = ['gloss'];

	public function semantic_fields()
	{
		return $this->belongsToMany(LexSemanticField::class, 'lex_etyma_semantic_field', 'etyma_id', 'semantic_field_id');
	}

	public function reflexes()
	{
		return $this->belongsToMany(LexReflex::class, 'lex_etyma_reflex', 'etyma_id', 'reflex_id');
	}

	public function cross_references()
	{
		return $this->belongsToMany(LexEtyma::class, 'lex_etyma_cross_reference', 'from_etyma_id', 'to_etyma_id');
	}

    public function extra_data() : HasMany
    {
        return $this->hasMany(LexEtymaExtraData::class, 'etyma_id');
    }

    public function getSources()
    {
        return $this->reflexes->pluck('sources')
            ->flatten()
            ->unique('code')
            ->sortBy('code')
            ->pluck('display', 'code')
            ->toArray();
    }

	public function getPOSes()
	{
		//build list of parts of speech used by these reflexes.  This is a little more complicate.
		//A single pos might be made up of several.  So we buld a lookup list first.
		//then we break up the used pos and lookup each part.
		$pos_lookup = LexPartOfSpeech::posLookup();

		$poses = array();
        foreach ($this->reflexes->pluck('parts_of_speech')->flatten() as $pos) {
            $sub_poses = explode('.',$pos->text);
            foreach($sub_poses as $sub_pos) {
                if (!array_key_exists($sub_pos,$poses) && array_key_exists($sub_pos, $pos_lookup)) {
                    $poses[$sub_pos] = $pos_lookup[$sub_pos];
                }
            }
		}
		ksort($poses);
		return $poses;
	}

	public function prevEtyma()
	{
		return LexEtyma::where('order', '<', $this->order)->orderBy('order', 'desc')->first();
	}

	public function nextEtyma()
	{
		return LexEtyma::where('order', '>', $this->order)->orderBy('order')->first();
	}

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }
}
