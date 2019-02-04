<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexSemanticCategory
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $number
 * @property string|null $abbr
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexSemanticField[] $semantic_fields
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory whereAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSemanticCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexSemanticCategory extends Model {
	protected $table = 'lex_semantic_category';
	
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
	
	public function semantic_fields()
	{
		return $this->hasMany('\App\LexSemanticField', 'semantic_category_id', 'id')->orderBy('number');
	}
	
}
