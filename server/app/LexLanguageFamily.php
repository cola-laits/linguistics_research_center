<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LexLanguageFamily extends Model {
	protected $table = 'lex_language_family';
	
	public static function boot() {
		parent::boot();
	
		// event to happen on saving
		static::creating(function($table)  {
			$table->created_by = Auth::user()->getUsername();
			$table->updated_by = Auth::user()->getUsername();
		});
	
		// event to happen on updating
		static::updating(function($table)  {
			$table->updated_by = Auth::user()->getUsername();
		});
	}
	
	public function language_sub_families()
	{
		return $this->hasMany('\App\LexLanguageSubFamily', 'family_id', 'id')->orderBy('order');
	}
	
	public function getFamilyAttribute()
	{
		return strip_tags($this->name);
	}
}
