<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use function collect;
use App\Models\LexEtyma;
use App\Models\LexLanguage;
use App\Models\LexReflexPartOfSpeech;
use App\Models\LexSource;

/**
 * App\Models\LexReflex
 *
 * @property int $id
 * @property int $language_id
 * @property string|null $lang_attribute
 * @property string|null $class_attribute
 * @property string|null $gloss
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property array|null $entries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexEtyma[] $etymas
 * @property-read int|null $etymas_count
 * @property-read mixed $lang_abbr_entries_gloss
 * @property-read mixed $lang_abbr_gloss
 * @property-read mixed $reflex_lister
 * @property-read \App\Models\LexLanguage $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexReflexPartOfSpeech[] $parts_of_speech
 * @property-read int|null $parts_of_speech_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexSource[] $sources
 * @property-read int|null $sources_count
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereClassAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereEntries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereGloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereLangAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflex whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexReflex extends Model {

    use CrudTrait;

	protected $table = 'lex_reflex';
	protected $guarded = ['id'];

	protected $casts = [
	    'entries' => 'array',
    ];

    protected $appends = ['langAbbrGloss','langAbbrEntriesGloss'];

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

	public function getLangAbbrGlossAttribute() {
	    return $this->lang_attribute . ': ' . $this->gloss;
    }

    public function getLangAbbrEntriesGlossAttribute() {
        $entries_csv = collect($this->entries)->pluck('text')->join(', ');
        return $this->lang_attribute . ': '.$entries_csv.' (' . $this->gloss . ')';
    }

    public function etymas()
	{
		return $this->belongsToMany(LexEtyma::class, 'lex_etyma_reflex', 'reflex_id', 'etyma_id');
	}

	public function language()
	{
		return $this->belongsTo(LexLanguage::class);
	}

	public function parts_of_speech()
	{
		return $this->hasMany(LexReflexPartOfSpeech::class, 'reflex_id', 'id')->orderBy('order');
	}

	public function sources()
	{
		return $this->belongsToMany(LexSource::class, 'lex_reflex_source', 'reflex_id', 'source_id')->orderBy('code');
	}

	public function getDisplayPartsOfSpeech()
	{
		$string = "";
		$i=0;
		foreach($this->parts_of_speech as $pos){
			$string .= $pos->text;
			$i++;
			if ($i != count($this->parts_of_speech)) {
				$string .= '/';
			}
		}
		return $string;
	}

	public function getDisplaySources()
	{
		$string = "";
		$i=0;
		foreach($this->sources as $source){
			$string .= $source->code;
			$i++;
			if ($i != count($this->sources)) {
				$string .= '/';
			}
		}
		return $string;
	}

	public function getReflexListerAttribute()
	{
		$text = ($this->language()->first()->name) . ': ';
		$ctr = 0;
		foreach($this->entries as $entry) {
			$ctr += 1;
			if ($ctr > 1){
				$text .= ', ';
			}
			$text .= strip_tags($entry['text']);
		}
		return $text;
	}

    public function getEntriesCSV() {
        $text = "";
        $ctr = 0;
        foreach($this->entries as $entry) {
            $ctr += 1;
            if ($ctr > 1){
                $text .= ', ';
            }
            $text .= strip_tags($entry['text']);
        }
        return $text;
    }
}
