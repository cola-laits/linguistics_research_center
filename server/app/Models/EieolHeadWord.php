<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EieolHeadWord extends Model
{
    protected $table = 'eieol_head_word';

    public function elements(): HasMany
    {
        return $this->hasMany(EieolElement::class, 'head_word_id', 'id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(EieolLanguage::class);
    }

    public function etyma(): BelongsTo
    {
        return $this->belongsTo(LexEtyma::class);
    }

    protected function getWordWithoutSurroundingAngleBracketsAttribute()
    {
        return preg_replace('/^<(.*)>$/', '$1', $this->word);
    }
}
