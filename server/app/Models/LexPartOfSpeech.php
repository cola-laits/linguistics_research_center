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
use App\Models\LexReflex;

/**
 * App\Models\LexPartOfSpeech
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $display
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\App\Models\LexReflex[] $reflex
 * @property-read int|null $reflex_count
 * @method static Builder|LexPartOfSpeech newModelQuery()
 * @method static Builder|LexPartOfSpeech newQuery()
 * @method static Builder|LexPartOfSpeech query()
 * @method static Builder|LexPartOfSpeech whereCode($value)
 * @method static Builder|LexPartOfSpeech whereCreatedAt($value)
 * @method static Builder|LexPartOfSpeech whereCreatedBy($value)
 * @method static Builder|LexPartOfSpeech whereDisplay($value)
 * @method static Builder|LexPartOfSpeech whereId($value)
 * @method static Builder|LexPartOfSpeech whereUpdatedAt($value)
 * @method static Builder|LexPartOfSpeech whereUpdatedBy($value)
 * @mixin Eloquent
 */
class LexPartOfSpeech extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_part_of_speech';

	protected $guarded = ['id'];

    protected $translatable = ['display'];

	public function reflex()
	{
		return $this->hasMany(LexReflex::class, 'part_of_speech_id', 'id');
	}

	public static function posLookup()
	{
		$all_pos = LexPartOfSpeech::all();
		$pos_lookup = array();
		foreach ($all_pos as $pos) {
			$pos_lookup[$pos->code] = $pos->display;
		}
		return $pos_lookup;
	}

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }
}
