<?php

namespace App\Models;

use App\Models\LexEtyma;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\LexSemanticCategory;

/**
 * App\Models\LexSemanticField
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $number
 * @property string|null $abbr
 * @property int $semantic_category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexEtyma[] $etymas
 * @property-read int|null $etymas_count
 * @property-read \App\Models\LexSemanticCategory $semantic_category
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField whereAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField whereSemanticCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSemanticField whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexSemanticField extends Model {

    use CrudTrait;

	protected $table = 'lex_semantic_field';

	protected $fillable = ['text','number','abbr','semantic_category_id'];

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

	public function semantic_category()
	{
		return $this->belongsTo(LexSemanticCategory::class);
	}

    public function lexicon()
    {
        return $this->hasOneThrough(LexLexicon::class, LexSemanticCategory::class, 'id', 'id', 'semantic_category_id', 'lexicon_id');
    }

    public function etyma()
    {
        return $this->belongsToMany(LexEtyma::class, 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id')
            ->orderBy('order');
    }

    /** @deprecated use etyma() instead */
	public function etymas()
	{
		return $this->belongsToMany(LexEtyma::class, 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id')
            ->orderBy('order');
	}

}
