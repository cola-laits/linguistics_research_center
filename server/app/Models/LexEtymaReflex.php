<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LexEtymaReflex extends Model
{

    protected $table = 'lex_etyma_reflex';

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function etyma(): HasOne
    {
        return $this->hasOne(LexEtyma::class, 'id', 'etyma_id');
    }

    public function reflex(): HasOne
    {
        return $this->hasOne(LexReflex::class, 'id', 'reflex_id');
    }
}
