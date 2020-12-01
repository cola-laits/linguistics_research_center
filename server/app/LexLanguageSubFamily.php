<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexLanguageSubFamily
 *
 * @property int $id
 * @property string|null $name
 * @property int $order
 * @property int $family_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read mixed $family_sub_family
 * @property-read \App\LexLanguageFamily $language_family
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexLanguage[] $languages
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily whereFamilyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexLanguageSubFamily whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexLanguageSubFamily extends Model {

    use CrudTrait;

	protected $table = 'lex_language_sub_family';

	protected $guarded = ['id'];

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

	public function languages()
	{
		return $this->hasMany('\App\LexLanguage', 'sub_family_id', 'id')->orderBy('order');
	}

	public function language_family()
	{
		return $this->belongsTo('\App\LexLanguageFamily','family_id');
	}

	public function getFamilySubFamilyAttribute()
	{
		return strip_tags($this->language_family()->first()->name) . '->' . strip_tags($this->name);
	}

}
