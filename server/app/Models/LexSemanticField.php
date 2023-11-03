<?php

namespace App\Models;

use App\Models\LexEtyma;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read Collection|\App\Models\LexEtyma[] $etymas
 * @property-read int|null $etymas_count
 * @property-read \App\Models\LexSemanticCategory $semantic_category
 * @method static Builder|LexSemanticField newModelQuery()
 * @method static Builder|LexSemanticField newQuery()
 * @method static Builder|LexSemanticField query()
 * @method static Builder|LexSemanticField whereAbbr($value)
 * @method static Builder|LexSemanticField whereCreatedAt($value)
 * @method static Builder|LexSemanticField whereCreatedBy($value)
 * @method static Builder|LexSemanticField whereId($value)
 * @method static Builder|LexSemanticField whereNumber($value)
 * @method static Builder|LexSemanticField whereSemanticCategoryId($value)
 * @method static Builder|LexSemanticField whereText($value)
 * @method static Builder|LexSemanticField whereUpdatedAt($value)
 * @method static Builder|LexSemanticField whereUpdatedBy($value)
 * @mixin Eloquent
 */
class LexSemanticField extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_semantic_field';

	protected $fillable = ['text','number','abbr','semantic_category_id'];

    protected $translatable = ['text'];

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
