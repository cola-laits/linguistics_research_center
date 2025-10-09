<?php

namespace App\Models;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EieolSeries extends Model
{

    protected $table = 'eieol_series';

    protected $guarded = ['id'];

    public function lessons(): HasMany
    {
        return $this->hasMany(EieolLesson::class, 'series_id', 'id')->orderBy('order');
    }

    public function languages(): HasMany
    {
        return $this->hasMany(EieolSeriesLanguage::class, 'series_id', 'id')->orderBy('display');
    }

    public function lesson_languages()
    {
        return $this->belongsToMany(EieolLanguage::class, 'eieol_lesson', 'series_id', 'language_id')
            ->distinct('language_id');
    }

    public function getBibliographyLesson()
    {
        return Models\EieolLesson::where('series_id', $this->id)
            ->where('title', 'like', '%Bibliography%')
            ->first();
    }
}
