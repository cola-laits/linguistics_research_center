<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexLanguageFamily
 *
 * @property int $id
 * @property string|null $name
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read mixed $family
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexLanguageSubFamily[] $language_sub_families
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageFamily whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexLanguageFamily extends Model {
	protected $table = 'lex_language_family';

	protected $fillable = ['name','order'];

	public static function boot() {
		parent::boot();
	
		// event to happen on saving
		static::creating(function($table)  {
			$table->created_by = Auth::user()->username;
			$table->updated_by = Auth::user()->username;
		});
	
		// event to happen on updating
		static::updating(function($table)  {
			$table->updated_by = Auth::user()->username;
		});
	}
	
	public function language_sub_families()
	{
		return $this->hasMany('\App\LexLanguageSubFamily', 'family_id', 'id')->orderBy('order');
	}
	
	public function getFamilyAttribute()
	{
		return strip_tags($this->name);
	}
}
