<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EieolGrammar extends Model
{
    protected $table = 'eieol_grammar';

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(EieolLesson::class);
    }
}
