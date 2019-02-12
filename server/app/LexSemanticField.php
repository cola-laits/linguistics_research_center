<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexSemanticField
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $number
 * @property string|null $abbr
 * @property int $semantic_category_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexEtyma[] $etymas
 * @property-read \App\LexSemanticCategory $semantic_category
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField whereAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField whereSemanticCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticField whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexSemanticField extends Model {
	protected $table = 'lex_semantic_field';

	protected $fillable = ['text','number','abbr','semantic_category_id'];

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
	
	public function semantic_category()
	{
		return $this->belongsTo('\App\LexSemanticCategory');
	}
	
	public function etymas()
	{
		return $this->belongsToMany('\App\LexEtyma', 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id')
            ->orderBy('order');
	}
	
}
