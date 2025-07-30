<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LexReflex;

class LexPartOfSpeech extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_part_of_speech';

	protected $guarded = ['id'];

    protected $translatable = ['display'];

	public function reflex()
	{
		return $this->hasMany(LexReflex::class, 'part_of_speech_id', 'id');
	}

	public static function posLookup()
	{
		$all_pos = LexPartOfSpeech::all();
		$pos_lookup = array();
		foreach ($all_pos as $pos) {
			$pos_lookup[$pos->code] = $pos->display;
		}
		return $pos_lookup;
	}

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }
}
