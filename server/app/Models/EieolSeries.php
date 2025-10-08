<?php

namespace App\Models;

use App\Models;
use Illuminate\Database\Eloquent\Model;

class EieolSeries extends Model
{

    protected $table = 'eieol_series';

    protected $guarded = ['id'];

    public function lessons()
    {
        return $this->hasMany(EieolLesson::class, 'series_id', 'id')->orderBy('order');
    }

    public function languages()
    {
        return $this->hasMany(EieolSeriesLanguage::class, 'series_id', 'id')->orderBy('display');
    }

    public function lesson_languages()
    {
        return $this->belongsToMany(EieolLanguage::class, 'eieol_lesson', 'series_id', 'language_id')
            ->distinct('language_id');
    }

    public static function findByIdOrSlug($text)
    {
        // series are referenced by slug, but there's still calls out there that reference them by DB PK.
        if (is_numeric($text)) {
            return self::findOrFail($text);
        }

        return self::where("slug", $text)->firstOrFail();
    }

    public function getBibliographyLesson()
    {
        return Models\EieolLesson::where('series_id', $this->id)
            ->where('title', 'like', '%Bibliography%')
            ->first();
    }
}
