<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\LexLanguageFamily;
use App\Models\LexLanguage;

/**
 * App\Models\LexLanguageSubFamily
 *
 * @property int $id
 * @property string|null $name
 * @property int $order
 * @property int $family_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read mixed $family_sub_family
 * @property-read \App\Models\LexLanguageFamily $language_family
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexLanguage[] $languages
 * @property-read int|null $languages_count
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily whereFamilyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageSubFamily whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexLanguageSubFamily extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_language_sub_family';

	protected $guarded = ['id'];

    protected $translatable = ['name'];

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
		return $this->hasMany(LexLanguage::class, 'sub_family_id', 'id')
            ->orderBy('order');
	}

	public function language_family()
	{
		return $this->belongsTo(LexLanguageFamily::class, 'family_id');
	}

	public function getFamilySubFamilyAttribute()
	{
		return strip_tags($this->language_family()->first()->name) . '->' . strip_tags($this->name);
	}

}
