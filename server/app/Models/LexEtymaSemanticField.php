<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LexEtymaSemanticField extends Model
{
    protected $table = 'lex_etyma_semantic_field';

    protected $guarded = ['id'];

    public function etyma(): HasOne
    {
        return $this->hasOne(LexEtyma::class, 'id', 'etyma_id');
    }

    public function semantic_field(): HasOne
    {
        return $this->hasOne(LexSemanticField::class, 'id', 'semantic_field_id');
    }
}
