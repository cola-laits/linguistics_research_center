<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexReflex
 *
 * @property int $id
 * @property int $language_id
 * @property string|null $lang_attribute
 * @property string|null $class_attribute
 * @property string|null $gloss
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexReflexEntry[] $entries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexEtyma[] $etymas
 * @property-read mixed $reflex_lister
 * @property-read \App\LexLanguage $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexReflexPartOfSpeech[] $parts_of_speech
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexSource[] $sources
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex whereClassAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex whereGloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex whereLangAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflex whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexReflex extends Model {

    use CrudTrait;

	protected $table = 'lex_reflex';
	protected $guarded = ['id'];

	protected $casts = [
	    'entries' => 'array'
    ];

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

    public function etymas()
	{
		return $this->belongsToMany('\App\LexEtyma', 'lex_etyma_reflex', 'reflex_id', 'etyma_id');
	}

	public function language()
	{
		return $this->belongsTo('\App\LexLanguage');
	}

	public function parts_of_speech()
	{
		return $this->hasMany('\App\LexReflexPartOfSpeech', 'reflex_id', 'id')->orderBy('order');
	}

	public function sources()
	{
		return $this->belongsToMany('\App\LexSource', 'lex_reflex_source', 'reflex_id', 'source_id')->orderBy('code');
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
