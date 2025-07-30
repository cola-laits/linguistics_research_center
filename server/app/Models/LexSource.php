<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LexReflex;

class LexSource extends Model {

    use CrudTrait;

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
