<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class LexReflexPartOfSpeech extends Model
{

    protected $table = 'lex_reflex_part_of_speech';

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function reflex(): BelongsTo
    {
        return $this->belongsTo(LexReflex::class);
    }

    public function part_of_speech(): HasOne
    {
        return $this->hasOne(LexPartOfSpeech::class, 'id', 'part_of_speech_id');
    }

    public function language(): HasOneThrough
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
