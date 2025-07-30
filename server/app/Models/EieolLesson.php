<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EieolLesson extends Model {
    use CrudTrait;
	protected $table = 'eieol_lesson';

	protected $attributes = array(
			'lesson_translation' => ' '
	);

    protected $guarded = ['id'];

    public function series()
	{
		return $this->belongsTo(EieolSeries::class);
	}

	public function grammars()
	{
		return $this->hasMany(EieolGrammar::class, 'lesson_id', 'id')->orderBy('order');
	}

	public function glossed_texts()
	{
		return $this->hasMany(EieolGlossedText::class, 'lesson_id', 'id')->orderBy('order');
	}

	public function language()
	{
		return $this->belongsTo(EieolLanguage::class,'language_id');
	}

	public function prevLesson()
	{
		return EieolLesson::where('order', '<', $this->order)->where('series_id', '=', $this->series_id)->orderBy('order', 'desc')->first();
	}

	public function nextLesson()
	{
		return EieolLesson::where('order', '>', $this->order)->where('series_id', '=', $this->series_id)->orderBy('order')->first();
	}

}
