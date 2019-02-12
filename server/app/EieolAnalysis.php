<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\EieolAnalysis
 *
 * @property int $id
 * @property string|null $analysis
 * @property int $language_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis whereAnalysis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolAnalysis whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolAnalysis extends Model {
	protected $table = 'eieol_analysis';
	
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
}
