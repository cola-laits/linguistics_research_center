<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\LexLanguageSubFamily;

/**
 * App\Models\LexLanguageFamily
 *
 * @property int $id
 * @property string|null $name
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read mixed $family
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexLanguageSubFamily[] $language_sub_families
 * @property-read int|null $language_sub_families_count
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexLanguageFamily whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexLanguageFamily extends Model {

    use CrudTrait;

	protected $table = 'lex_language_family';

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

	public function language_sub_families()
	{
		return $this->hasMany(LexLanguageSubFamily::class, 'family_id', 'id')
            ->orderBy('order');
	}

	public function getFamilyAttribute()
	{
		return strip_tags($this->name);
	}

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }
}
