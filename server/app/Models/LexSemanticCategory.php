<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class LexSemanticCategory extends Model
{
    use HasTranslations;

    protected $table = 'lex_semantic_category';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $translatable = ['text'];

    public function semantic_fields()
    {
        return $this->hasMany(LexSemanticField::class, 'semantic_category_id', 'id')->orderBy('number');
    }

    public function lexicon(): BelongsTo
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }

    public function getLexTextAttribute(): string
    {
        return $this->lexicon->name . ' - ' . $this->text;
    }
}
