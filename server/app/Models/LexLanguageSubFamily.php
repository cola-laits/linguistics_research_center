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
use App\Models\LexLanguageFamily;
use App\Models\LexLanguage;

class LexLanguageSubFamily extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_language_sub_family';

	protected $guarded = ['id'];

    protected $translatable = ['name'];

	public function languages()
	{
		return $this->hasMany(LexLanguage::class, 'sub_family_id', 'id')
            ->orderBy('order');
	}

	public function language_family()
	{
		return $this->belongsTo(LexLanguageFamily::class, 'family_id');
	}

	public function getFamilySubFamilyAttribute()
	{
		return strip_tags($this->language_family()->first()->name) . '->' . strip_tags($this->name);
	}

}
