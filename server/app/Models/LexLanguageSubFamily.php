<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LexLanguageSubFamily extends Model
{

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
