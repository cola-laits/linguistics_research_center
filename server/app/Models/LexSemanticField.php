<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class LexSemanticField extends Model
{

    use HasTranslations;

    protected $table = 'lex_semantic_field';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $translatable = ['text'];

    protected $appends = ['lexiconNameText', 'lexiconNameAbbrText'];

    public function semantic_category(): BelongsTo
    {
        return $this->belongsTo(LexSemanticCategory::class);
    }

    public function lexicon()
    {
        return $this->hasOneThrough(LexLexicon::class, LexSemanticCategory::class, 'id', 'id', 'semantic_category_id', 'lexicon_id');
    }

    public function etyma()
    {
        return $this->belongsToMany(LexEtyma::class, 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id')
            ->orderBy('order');
    }

    /** @deprecated use etyma() instead */
    public function etymas()
    {
        return $this->belongsToMany(LexEtyma::class, 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id')
            ->orderBy('order');
    }

    public function getLexiconNameTextAttribute(): string
    {
        return $this->lexicon->name . ': ' . $this->text;
    }

    public function getLexiconNameAbbrTextAttribute(): string
    {
        return $this->lexicon->name . ': ' . $this->abbr . ', ' . $this->text;
    }
}
