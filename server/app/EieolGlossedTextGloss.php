<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolGlossedTextGloss
 *
 * @property int $id
 * @property int $glossed_text_id
 * @property int $gloss_id
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\EieolGloss $gloss
 * @property-read \App\EieolGlossedText $glossed_text
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss whereGlossId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss whereGlossedTextId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedTextGloss whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolGlossedTextGloss extends Model {
	protected $table = 'eieol_glossed_text_gloss';
	
	public function glossed_text()
	{
		return $this->hasOne('\App\EieolGlossedText','id','glossed_text_id');
	}
	
	public function gloss()
	{
		return $this->hasOne('\App\EieolGloss','id','gloss_id');
	}
}
