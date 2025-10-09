<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LexReflexSource extends Model
{
    protected $table = 'lex_reflex_source';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function reflex(): HasOne
    {
        return $this->hasOne(LexReflex::class, 'id', 'reflex_id');
    }

    public function source(): HasOne
    {
        return $this->hasOne(LexSource::class, 'id', 'source_id');
    }
}
