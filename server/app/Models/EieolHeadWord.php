<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EieolHeadWord extends Model
{
    protected $table = 'eieol_head_word';

    public function elements()
    {
        return $this->hasMany(EieolElement::class, 'head_word_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(EieolLanguage::class);
    }

    public function etyma()
    {
        return $this->belongsTo(LexEtyma::class);
    }

    protected function getWordWithoutSurroundingAngleBracketsAttribute()
    {
        return preg_replace('/^<(.*)>$/', '$1', $this->word);
    }
}
