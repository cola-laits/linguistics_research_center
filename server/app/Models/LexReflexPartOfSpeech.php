<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LexReflex;
use App\Models\LexPartOfSpeech;

/**
 * App\Models\LexReflexPartOfSpeech
 *
 * @property int $id
 * @property int $reflex_id
 * @property string|null $text
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\LexPartOfSpeech|null $part_of_speech
 * @property-read \App\Models\LexReflex $reflex
 * @method static Builder|LexReflexPartOfSpeech newModelQuery()
 * @method static Builder|LexReflexPartOfSpeech newQuery()
 * @method static Builder|LexReflexPartOfSpeech query()
 * @method static Builder|LexReflexPartOfSpeech whereCreatedAt($value)
 * @method static Builder|LexReflexPartOfSpeech whereCreatedBy($value)
 * @method static Builder|LexReflexPartOfSpeech whereId($value)
 * @method static Builder|LexReflexPartOfSpeech whereOrder($value)
 * @method static Builder|LexReflexPartOfSpeech whereReflexId($value)
 * @method static Builder|LexReflexPartOfSpeech whereText($value)
 * @method static Builder|LexReflexPartOfSpeech whereUpdatedAt($value)
 * @method static Builder|LexReflexPartOfSpeech whereUpdatedBy($value)
 * @property-read \App\Models\LexLanguage|null $language
 * @mixin Eloquent
 */
class LexReflexPartOfSpeech extends Model {

    use CrudTrait;

	protected $table = 'lex_reflex_part_of_speech';

    protected $guarded = [
        'id','created_at','updated_at'
    ];

	public function reflex()
	{
		return $this->belongsTo(LexReflex::class);
	}

	public function part_of_speech()
	{
		return $this->hasOne(LexPartOfSpeech::class, 'id', 'part_of_speech_id');
	}

    public function language()
    {
        return $this->hasOneThrough(
            LexLanguage::class,
            LexReflex::class,
            'id',
            'id',
            'reflex_id',
            'language_id'
        );
    }
}
