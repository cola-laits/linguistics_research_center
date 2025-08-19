<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class LexLanguageFamily extends Model {

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

    public function lexicon() : BelongsTo
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }
}
