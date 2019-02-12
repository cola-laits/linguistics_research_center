<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexSource
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $display
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexReflex[] $reflex
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexSource whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexSource extends Model {
	protected $table = 'lex_source';

	protected $fillable = ['code','display'];

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
	
	public function reflex()
	{
		return $this->hasMany('\App\LexReflex', 'source_id', 'id');
	}
}
