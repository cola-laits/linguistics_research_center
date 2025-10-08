<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EieolGrammar extends Model
{
    protected $table = 'eieol_grammar';

    public function lesson()
    {
        return $this->belongsTo(EieolLesson::class);
    }
}
