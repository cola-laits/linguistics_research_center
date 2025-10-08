<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;


class LexReflex extends Model
{

    use HasTranslations;

    protected $table = 'lex_reflex';
    protected $guarded = ['id'];
    protected $translatable = ['gloss'];

    protected $casts = [
        'entries' => 'array',
    ];

    protected $appends = ['langAbbrGloss', 'langNameEntriesGloss'];

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

        $short = $first . $last;
        $long = $first . $middle . $last;

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
        return implode('', $key_parts) . $key;
    }

    public function getLangAbbrGlossAttribute()
    {
        return $this->lang_attribute . ': ' . $this->gloss;
    }

    public function getLangNameEntriesGlossAttribute()
    {
        $entries_csv = collect($this->entries)->pluck('text')->join(', ');
        return $this->language->name . ': ' . $entries_csv . ' (' . $this->gloss . ')';
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

    public function language(): BelongsTo
    {
        return $this->belongsTo(LexLanguage::class);
    }

    public function parts_of_speech(): HasMany
    {
        return $this->hasMany(LexReflexPartOfSpeech::class, 'reflex_id')->orderBy('order');
    }

    public function sources()
    {
        return $this->belongsToMany(LexSource::class, 'lex_reflex_source', 'reflex_id', 'source_id')
            ->withPivot('page_number', 'original_text')
            ->orderBy('code');
    }

    public function cross_references_to()
    {
        return $this->belongsToMany(LexReflex::class, 'lex_reflex_cross_reference', 'from_reflex_id', 'to_reflex_id')
            ->withPivot(['relationship'])
            ->using(LexReflexCrossReference::class);
    }

    public function cross_references_from()
    {
        return $this->belongsToMany(LexReflex::class, 'lex_reflex_cross_reference', 'to_reflex_id', 'from_reflex_id')
            ->withPivot(['relationship'])
            ->using(LexReflexCrossReference::class);
    }

    public function cross_reference_to_pivots()
    {
        return $this->hasMany(LexReflexCrossReference::class, 'to_reflex_id');
    }

    public function extra_data(): HasMany
    {
        return $this->hasMany(LexReflexExtraData::class, 'reflex_id');
    }

    public function etymaSemanticTags()
    {
        return $this->etymas->pluck('semantic_fields')->flatten();
    }

    public function getDisplayPartsOfSpeech()
    {
        return $this->parts_of_speech->pluck('text')->join('/');
    }

    public function getDisplaySources()
    {
        return $this->sources->pluck('code')->join('/');
    }

    public function getEntriesCSV()
    {
        return collect($this->entries)
            ->pluck('text')
            ->map(fn($text) => strip_tags($text))
            ->join(', ');
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
                    'id' => $etyma->old_id,
                    'homograph_number' => $etyma->homograph_number,
                ])->sortBy('id')->toArray();

                $new_key = LexReflex::hashKey($key, $alpha_weights);
                $these_reflexes[$new_key] = [
                    'id' => $this->id,
                    'reflex' => $key,
                    'lang_attribute' => $this->lang_attribute,
                    'etymas' => $etymas
                ];
            }
        }
        return $these_reflexes;
    }
}
