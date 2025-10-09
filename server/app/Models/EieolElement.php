<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EieolElement extends Model
{
    protected $table = 'eieol_element';

    public function head_word(): BelongsTo
    {
        return $this->belongsTo(EieolHeadWord::class);
    }

    public function gloss(): BelongsTo
    {
        return $this->belongsTo(EieolGloss::class);
    }
}
