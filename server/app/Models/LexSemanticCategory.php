<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\LexSemanticField;

/**
 * App\Models\LexSemanticCategory
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $number
 * @property string|null $abbr
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexSemanticField[] $semantic_fields
 * @property-read int|null $semantic_fields_count
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory whereAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexSemanticCategory extends Model {

    use CrudTrait;

	protected $table = 'lex_semantic_category';

	protected $fillable = ['number','text','abbr'];

	public static function boot() {
		parent::boot();

		// event to happen on saving
		static::creating(function($table)  {
            if (Auth::user()) {
                $table->created_by = Auth::user()->username;
                $table->updated_by = Auth::user()->username;
            }
		});

		// event to happen on updating
		static::updating(function($table)  {
            if (Auth::user()) {
                $table->updated_by = Auth::user()->username;
            }
		});
	}

	public function semantic_fields()
	{
		return $this->hasMany(LexSemanticField::class, 'semantic_category_id', 'id')->orderBy('number');
	}

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }
}
