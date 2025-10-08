<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LexLanguage extends Model
{

    use HasTranslations;

    protected $table = 'lex_language';

    protected $guarded = ['id'];

    protected $translatable = ['name', 'description'];

    public function language_sub_family()
    {
        return $this->belongsTo(LexLanguageSubFamily::class, 'sub_family_id');
    }

    public function reflexes()
    {
        return $this->hasMany(LexReflex::class, 'language_id', 'id')
            ->orderBy('entries');
    }

    public function small_reflexes()
    {
        return $this->hasMany(LexReflex::class, 'language_id', 'id');
    }

    public function reflex_count()
    {
        return $this->hasMany(LexReflex::class, 'language_id', 'id')->select(DB::raw('language_id, count(*) as count'))->groupBy('language_id');
    }

    public function getStrippedNameAttribute()
    {
        return strip_tags($this->name);
    }

    public function displayFamily()
    {
        if ($this->override_family != '') {
            return $this->override_family;
        } else {
            return $this->language_sub_family->language_family->name;
        }
    }
}
