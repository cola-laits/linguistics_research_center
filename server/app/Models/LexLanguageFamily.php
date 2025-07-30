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
use App\Models\LexLanguageSubFamily;

class LexLanguageFamily extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_language_family';

	protected $guarded = ['id'];

    protected $translatable = ['name'];

	public function language_sub_families()
	{
		return $this->hasMany(LexLanguageSubFamily::class, 'family_id', 'id')
            ->orderBy('order');
	}

	public function getFamilyAttribute()
	{
		return strip_tags($this->name);
	}

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }
}
