<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|LexEtyma[] $cross_references
 * @property-read int|null $cross_references_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexReflex[] $reflexes
 * @property-read int|null $reflexes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexSemanticField[] $semantic_fields
 * @property-read int|null $semantic_fields_count
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma whereEntry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma whereGloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma whereOldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma wherePageNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtyma whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexEtyma extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_etyma';
	protected $guarded = ['id'];
    protected $casts = [
        'extra_data' => 'array'
    ];
    protected $translatable = ['gloss'];

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

    public function getReflexesLangNameEntriesGloss() {
        $text = $this->reflexes->map(function($reflex) {
            return $reflex->langNameEntriesGloss;
        })->implode(', ');
        if (strlen($text) > 30) {
            $text = substr($text, 0, 30) . '...';
        }
        return $text;
    }

	public function getSources()
	{
		//build list of sources used by all relfexes for this etyma reflexes
		$sources = array();
		foreach ($this->reflexes as $reflex) {
			foreach($reflex->sources as $source) {
				if (!array_key_exists($source->code,$sources)) {
					$sources[$source->code] = $source->display;
				}
			}
		}
		ksort($sources);
		return $sources;
	}

	public function getPOSes()
	{
		//build list of parts of speech used by these reflexes.  This is a little more complicate.
		//A single pos might be made up of several.  So we buld a lookup list first.
		//then we break up the used pos and lookup each part.
		$pos_lookup = LexPartOfSpeech::posLookup();

		$poses = array();
		foreach ($this->reflexes as $reflex) {
			foreach($reflex->parts_of_speech as $pos) {
				$sub_poses = explode('.',$pos->text);
				foreach($sub_poses as $sub_pos) {
					if (!array_key_exists($sub_pos,$poses) && array_key_exists($sub_pos, $pos_lookup)) {
						$poses[$sub_pos] = $pos_lookup[$sub_pos];
					}
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
