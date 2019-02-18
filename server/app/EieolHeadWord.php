<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolHeadWord
 *
 * @property int $id
 * @property string|null $word
 * @property string|null $definition
 * @property int $language_id
 * @property int|null $etyma_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolElement[] $elements
 * @property-read \App\LexEtyma|null $etyma
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolHeadWordKeyword[] $keywords
 * @property-read \App\EieolLanguage $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord whereEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWord whereWord($value)
 * @mixin \Eloquent
 */
class EieolHeadWord extends Model {
	protected $table = 'eieol_head_word';
	
	public function keywords()
	{
		return $this->hasMany('\App\EieolHeadWordKeyword', 'head_word_id', 'id')->orderBy('keyword');
	}
	
	public function elements()
	{
		return $this->hasMany('\App\EieolElement', 'head_word_id', 'id');
	}
	
	public function language()
	{
		return $this->belongsTo('\App\EieolLanguage');
	}
	
	public function etyma()
	{
		return $this->belongsTo('\App\LexEtyma');
	}
	
	public function getDisplayHeadWord()
	{
		//return headword and definition in the format <span style="white-space: nowrap" lang='cu' class='Cyrillic'>&lt; Ñ¥Ñ�-, Ñ¥Ñ�Ð¼ÑŒ, Ñ¥Ñ�Ð¸&gt;</span> be
		//trim <>
		$word = $this->word;
		$word = substr($word,1);
		$word = substr($word,0,-1);
		
		return "<span style='white-space: nowrap' lang='" . $this->language->lang_attribute . "'> &lt;" . $word .  "&gt;</span> "  . $this->definition;
	}
}
