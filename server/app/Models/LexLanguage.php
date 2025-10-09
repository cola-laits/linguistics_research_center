<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class LexLanguage extends Model
{

    use HasTranslations;

    protected $table = 'lex_language';

    protected $guarded = ['id'];

    protected $translatable = ['name', 'description'];

    public function language_sub_family(): BelongsTo
    {
        return $this->belongsTo(LexLanguageSubFamily::class, 'sub_family_id');
    }

    public function reflexes(): HasMany
    {
        return $this->hasMany(LexReflex::class, 'language_id', 'id')
            ->orderBy('entries');
    }

    public function small_reflexes(): HasMany
    {
        return $this->hasMany(LexReflex::class, 'language_id', 'id');
    }

    public function reflex_count(): HasMany
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
