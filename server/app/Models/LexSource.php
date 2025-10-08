<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function lexiconNameCode(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->lexicon->name . ': ' . $this->code
        );
    }

    protected function lexiconNameCodeTitle(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->lexicon->name . ': ' . $this->code . ' (' . $this->display . ')'
        );
    }
}
