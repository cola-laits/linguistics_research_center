<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexEtyma
 *
 * @property int $id
 * @property string|null $old_id
 * @property int $order
 * @property string|null $page_number
 * @property string|null $entry
 * @property string|null $gloss
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexEtyma[] $cross_references
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexReflex[] $reflexes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexSemanticField[] $semantic_fields
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma whereEntry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma whereGloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma whereOldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma wherePageNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtyma whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexEtyma extends Model {
	protected $table = 'lex_etyma';
	
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
		return $this->belongsToMany('\App\LexSemanticField', 'lex_etyma_semantic_field', 'etyma_id', 'semantic_field_id');
	}
	
	public function reflexes()
	{
		return $this->belongsToMany('\App\LexReflex', 'lex_etyma_reflex', 'etyma_id', 'reflex_id');
	}
	
	public function cross_references()
	{
		return $this->belongsToMany('\App\LexEtyma', 'lex_etyma_cross_reference', 'from_etyma_id', 'to_etyma_id');
	}
	
	
	public function getSources()
	{
		//build list of sources used by all relfexes for this etyma reflexes
		$sources = array();
		foreach ($this->reflexes as $reflex) {
			foreach($reflex->sources as $source) {
				if (!array_key_exists($source->code,$sources)) {
					$sources[$source->code] = $source->display;
				}
			}
		}
		ksort($sources);
		return $sources;
	}
	
	public function getPOSes()
	{
		//build list of parts of speech used by these reflexes.  This is a little more complicate.
		//A single pos might be made up of several.  So we buld a lookup list first.
		//then we break up the used pos and lookup each part.
		$pos_lookup = LexPartOfSpeech::posLookup();
		
		$poses = array();
		foreach ($this->reflexes as $reflex) {
			foreach($reflex->parts_of_speech as $pos) {
				$sub_poses = explode('.',$pos->text);
				foreach($sub_poses as $sub_pos) {
					if (!array_key_exists($sub_pos,$poses) && array_key_exists($sub_pos, $pos_lookup)) {
						$poses[$sub_pos] = $pos_lookup[$sub_pos];
					}
				}
			}
		}
		ksort($poses);
		return $poses;
	}
	
	public function prevEtyma()
	{
		return LexEtyma::where('order', '<', $this->order)->orderBy('order', 'desc')->first();
	}
	
	public function nextEtyma()
	{
		return LexEtyma::where('order', '>', $this->order)->orderBy('order')->first();
	}
		
}
