<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

class LexSource extends Model {

	protected $table = 'lex_source';

	protected $guarded = ['id'];

	public function reflex()
	{
		return $this->hasMany(LexReflex::class, 'source_id', 'id');
	}

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }

    public function getLexiconNameCodeAttribute() {
        return $this->lexicon->name . ': ' . $this->code;
    }
}
