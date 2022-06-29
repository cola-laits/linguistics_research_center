<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class LexLexicon extends Model
{
    use CrudTrait;

    protected $table = 'lex_lexicon';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function etyma() {
        return $this->hasMany(LexEtyma::class, 'lexicon_id');
    }

    public function semantic_categories() {
        return $this->hasMany(LexSemanticCategory::class, 'lexicon_id')
            ->orderBy('number');
    }

    public function language_families() {
        return $this->hasMany(LexLanguageFamily::class, 'lexicon_id')
            ->orderBy('order');
    }
}
