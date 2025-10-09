<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EieolLesson extends Model
{

    protected $table = 'eieol_lesson';

    protected $attributes = array(
        'lesson_translation' => ' '
    );

    protected $guarded = ['id'];

    public function series(): BelongsTo
    {
        return $this->belongsTo(EieolSeries::class);
    }

    public function grammars(): HasMany
    {
        return $this->hasMany(EieolGrammar::class, 'lesson_id', 'id')->orderBy('order');
    }

    public function glossed_texts(): HasMany
    {
        return $this->hasMany(EieolGlossedText::class, 'lesson_id', 'id')->orderBy('order');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(EieolLanguage::class, 'language_id');
    }

    public function prevLesson()
    {
        return EieolLesson::where('order', '<', $this->order)->where('series_id', '=', $this->series_id)->orderBy('order', 'desc')->first();
    }

    public function nextLesson()
    {
        return EieolLesson::where('order', '>', $this->order)->where('series_id', '=', $this->series_id)->orderBy('order')->first();
    }

    public function getTinyMceLanguages(): array
    {
        $langs = collect();
        if ($this->language) {
            $langs->add((object)['title' => $this->language->language, 'code' => $this->language->lang_attribute]);
        }
        foreach ($this->series->languages as $lang) {
            $langs->add((object)['title' => $lang->display, 'code' => $lang->lang]);
        }
        return $langs->toArray();
    }
}
