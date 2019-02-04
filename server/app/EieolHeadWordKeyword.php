<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolHeadWordKeyword
 *
 * @property int $id
 * @property int $head_word_id
 * @property string|null $keyword
 * @property int $language_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\EieolHeadWord $head_word
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword whereHeadWordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolHeadWordKeyword whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolHeadWordKeyword extends Model {
	protected $table = 'eieol_head_word_keyword';
	protected $fillable = array('keyword', 'language_id', 'created_by', 'updated_by');
	
	public function head_word()
	{
		return $this->belongsTo('\App\EieolHeadWord');
	}
}
