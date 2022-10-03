<?php

namespace App\Models;

use App\Http\Controllers\PublicIELexController;
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
        'extra_data' => 'array',
    ];

    protected $appends = ['langAbbrGloss','langNameEntriesGloss'];

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

    private static function split_entries($entry)
    {
        //entries might have some characters in ().  This means the entry is actually 2 entries, eg: Farv(e) would be Farv and Farve.
        //it is possible for an entry to have multiple parens, in which case we call this routine recursively.
        $open = mb_strpos($entry, '(', 0, 'UTF-8');
        $close = mb_strpos($entry, ')', 0, 'UTF-8');
        $first = mb_substr($entry, 0, $open, 'UTF-8');

        $len = $close - $open;
        $middle = mb_substr($entry, $open + 1, $len - 1, 'UTF-8');

        $len = mb_strlen($entry, 'UTF-8') - $close;
        $last = mb_substr($entry, $close + 1, $len, 'UTF-8');

        $short = $first.$last;
        $long = $first.$middle.$last;

        $keys = array();

        if (mb_strpos($short, '(', 0, 'UTF-8') === false) {
            $keys[] = $short;
        } else {
            $keys = array_merge($keys, self::split_entries($short));
        }

        if (mb_strpos($long, '(', 0, 'UTF-8') === false) {
            $keys[] = $long;
        } else {
            $keys = array_merge($keys, self::split_entries($long));
        }

        return $keys;
    }

    private static function hashKey($key, $alpha_weights)
    {
        //convert the key reflex to a series of numbers based on the weighted alphabet array for easy sorting.

        //break string into an array
        $key_array = preg_split('//u', $key, -1, PREG_SPLIT_NO_EMPTY);

        //build a hash of entry using weights.  So ab would become something like 00010002
        $key_parts = array_map(function ($key_char) use ($alpha_weights) {
            //these characters will not be used when sorting the keys of the array
            $the_unwanted = ["-", "*", "'"];
            if (in_array($key_char, $the_unwanted)) { //remove any unwanted characters
                return '';
            }
            if (array_key_exists($key_char, $alpha_weights)) {
                return str_pad($alpha_weights[$key_char], 4, '0', STR_PAD_LEFT);
            }

            return '0000'; //unknown characters become 0000 so they show up first
        }, $key_array);

        //Tack the original entry on to the end.  This way the keys remain unique even if it had unwanted chars, but the ending isn't really used for sorting
        return implode('', $key_parts).$key;
    }

    public function getLangAbbrGlossAttribute() {
	    return $this->lang_attribute . ': ' . $this->gloss;
    }

    public function getLangNameEntriesGlossAttribute() {
        $entries_csv = collect($this->entries)->pluck('text')->join(', ');
        return $this->language->name . ': '.$entries_csv.' (' . $this->gloss . ')';
    }

    public function etyma()
    {
        return $this->belongsToMany(LexEtyma::class, 'lex_etyma_reflex', 'reflex_id', 'etyma_id');
    }

    /** @deprecated use etyma() instead */
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

    public function get_collatable_entries(array $alpha_weights): array
    {
        $these_reflexes = [];
        foreach ($this->entries as $entry) {
            //special processing based on whether or not the entry has a ( in it
            $lacks_separator = mb_strpos($entry['text'], '(', 0, 'UTF-8') === false;
            $keys = $lacks_separator ? [$entry['text']] : LexReflex::split_entries($entry['text']);
            foreach ($keys as $key) {
                $etymas = $this->etymas->map(fn($etyma) => [
                    'entry' => $etyma->entry,
                    'gloss' => $etyma->gloss,
                    'id' => $etyma->old_id
                ])->sortBy('id')->toArray();

                $new_key = LexReflex::hashKey($key, $alpha_weights);
                $these_reflexes[$new_key] = [
                    'id' => $this->id,
                    'reflex' => $key,
                    'class_attribute' => $this->class_attribute,
                    'lang_attribute' => $this->lang_attribute,
                    'etymas' => $etymas
                ];
            }
        }
        return $these_reflexes;
    }
}
