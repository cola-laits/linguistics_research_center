<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class LexPartOfSpeech extends Model
{

    use HasTranslations;

    protected $table = 'lex_part_of_speech';

    protected $guarded = ['id'];

    protected $translatable = ['display'];

    public function reflex(): HasMany
    {
        return $this->hasMany(LexReflex::class, 'part_of_speech_id', 'id');
    }

    public function lexicon(): BelongsTo
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
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
}
