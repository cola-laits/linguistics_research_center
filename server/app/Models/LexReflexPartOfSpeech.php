<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LexReflex;
use App\Models\LexPartOfSpeech;

class LexReflexPartOfSpeech extends Model {

    use CrudTrait;

	protected $table = 'lex_reflex_part_of_speech';

    protected $guarded = [
        'id','created_at','updated_at'
    ];

	public function reflex()
	{
		return $this->belongsTo(LexReflex::class);
	}

	public function part_of_speech()
	{
		return $this->hasOne(LexPartOfSpeech::class, 'id', 'part_of_speech_id');
	}

    public function language()
    {
        return $this->hasOneThrough(
            LexLanguage::class,
            LexReflex::class,
            'id',
            'id',
            'reflex_id',
            'language_id'
        );
    }
}
