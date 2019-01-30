<?php 

class LexEtyma extends Eloquent {
	protected $table = 'lex_etyma';
	
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
		
	public function semantic_fields()
	{
		return $this->belongsToMany('LexSemanticField', 'lex_etyma_semantic_field', 'etyma_id', 'semantic_field_id');
	}
	
	public function reflexes()
	{
		return $this->belongsToMany('LexReflex', 'lex_etyma_reflex', 'etyma_id', 'reflex_id');
	}
	
	public function reflex_count()
	{
		return $this->belongsToMany('LexReflex', 'lex_etyma_reflex', 'etyma_id', 'reflex_id')->selectRaw('count(reflex_id) as count')->groupBy('pivot_etyma_id');
		
	}
	
	public function cross_references()
	{
		return $this->belongsToMany('LexEtyma', 'lex_etyma_cross_reference', 'from_etyma_id', 'to_etyma_id');
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
					if (!array_key_exists($sub_pos,$poses)) {
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