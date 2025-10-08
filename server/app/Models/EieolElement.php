<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EieolElement extends Model
{
    protected $table = 'eieol_element';

    public function head_word()
    {
        return $this->belongsTo(EieolHeadWord::class);
    }

    public function gloss()
    {
        return $this->belongsTo(EieolGloss::class);
    }
}
