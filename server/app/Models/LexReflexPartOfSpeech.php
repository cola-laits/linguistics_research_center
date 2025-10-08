<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LexReflexPartOfSpeech extends Model
{

    protected $table = 'lex_reflex_part_of_speech';

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function reflex()
    {
        return $this->belongsTo(LexReflex::class);
    }

    public function part_of_speech()
    {
        return $this->hasOne(LexPartOfSpeech::class, 'id', 'part_of_speech_id');
    }

    public function language()
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
