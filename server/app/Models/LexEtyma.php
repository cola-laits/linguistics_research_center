<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class LexEtyma extends Model
{

    use HasTranslations;

    protected $table = 'lex_etyma';
    protected $guarded = ['id'];
    protected $translatable = ['gloss'];

    protected $appends = ['lexiconNameEntry', 'lexiconNameEntryGloss'];

    public function semantic_fields(): BelongsToMany
    {
        return $this->belongsToMany(LexSemanticField::class, 'lex_etyma_semantic_field', 'etyma_id', 'semantic_field_id');
    }

    public function reflexes(): BelongsToMany
    {
        return $this->belongsToMany(LexReflex::class, 'lex_etyma_reflex', 'etyma_id', 'reflex_id');
    }

    public function cross_references(): BelongsToMany
    {
        return $this->belongsToMany(LexEtyma::class, 'lex_etyma_cross_reference', 'from_etyma_id', 'to_etyma_id');
    }

    public function extra_data(): HasMany
    {
        return $this->hasMany(LexEtymaExtraData::class, 'etyma_id');
    }

    public function lexicon(): BelongsTo
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
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
            $sub_poses = explode('.', $pos->text);
            foreach ($sub_poses as $sub_pos) {
                if (!array_key_exists($sub_pos, $poses) && array_key_exists($sub_pos, $pos_lookup)) {
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

    public function getLexiconNameEntryAttribute()
    {
        return $this->lexicon->name . ': ' . $this->entry;
    }

    public function getLexiconNameEntryGlossAttribute()
    {
        return $this->lexicon->name . ': ' . $this->entry . ' (' . $this->gloss . ')';
    }
}
