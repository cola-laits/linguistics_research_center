<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LexLanguageSubFamily extends Model {
	protected $table = 'lex_language_sub_family';
	
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
	
	public function languages()
	{
		return $this->hasMany('\App\LexLanguage', 'sub_family_id', 'id')->orderBy('order');
	}
	
	public function language_family()
	{
		return $this->belongsTo('\App\LexLanguageFamily','family_id');
	}
	
	public function getFamilySubFamilyAttribute()
	{
		return strip_tags($this->language_family()->first()->name) . '->' . strip_tags($this->name);
	}
	
}
