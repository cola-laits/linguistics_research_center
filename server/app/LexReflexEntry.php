<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LexReflexEntry extends Model {
	protected $table = 'lex_reflex_entry';
	
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
			
	public function reflex()
	{
		return $this->belongsTo('\App\LexReflex');
	}
	
	private static function split_entries($entry) {
		//entries might have some characters in ().  This means the entry is actually 2 entries, eg: Farv(e) would be Farv and Farve.
		//it is possible for an entry to have multiple parens, in which case we call this routine recursively.
		$open = mb_strpos($entry,'(', 0,'UTF-8');
		$close = mb_strpos($entry,')', 0,'UTF-8');
		$first = mb_substr($entry, 0, $open, 'UTF-8');
	
		$len = $close - $open;
		$middle = mb_substr($entry, $open + 1, $len - 1, 'UTF-8');
	
		$len = mb_strlen($entry, 'UTF-8') - $close;
		$last = mb_substr($entry, $close + 1, $len, 'UTF-8');
	
		$short = $first . $last;
		$long = $first . $middle . $last;
	
		$keys = array();
	
		if (mb_strpos($short,'(', 0,'UTF-8') === False) {
			$keys[] = $short;
		} else {
			//print_r(split_entries($short));
			$keys = array_merge($keys,LexReflexEntry::split_entries($short));
		}
	
		if (mb_strpos($long,'(', 0,'UTF-8') === False) {
			$keys[] = $long;
		} else {
			$keys = array_merge($keys,LexReflexEntry::split_entries($long));
		}
	
		return $keys;
	} //split_entries function
	
	public static function keys($entry)
	{
		$keys=array();
		//special processing based on whether or not the entry has a ( in it
		if (mb_strpos($entry,'(', 0,'UTF-8') === False) {
			//regular entry
			$keys[] = $entry;
		} else {
			//if a reflex contains characters in (), split into 2, ex (g)nosco = gnosco and nosco in Latin
			$keys = LexReflexEntry::split_entries($entry);
		}
	
		return $keys;
	}
	
	public static function hashKey($key, $alpha_weights)
	{
		//convert the key reflex to a series of numbers based on the weighted alphabet array for easy sorting.
		$new_key = '';
		
		//these characters will not be used when sorting the keys of the array
		$the_unwanted = array("-", "*", "'");
		
		//break string into an array
		$key_array = preg_split('//u',$key, -1, PREG_SPLIT_NO_EMPTY);
				
		//build a hash of entry using weights.  So ab would become something like 00010002
		foreach($key_array as $key_char) {
			if (in_array($key_char,$the_unwanted)) { //remove any unwanted characters
				continue;
			} elseif (array_key_exists($key_char,$alpha_weights)) {
				$new_key .= str_pad($alpha_weights[$key_char], 4,'0', STR_PAD_LEFT);
			} else {
				$new_key .= '0000'; //unknown characters become 0000 so they show up first
			}
		}
		
		//Tack the original entry on to the end.  This way the keys remain unique even if it had unwanted chars, but the ending isn't really used for sorting
		$new_key .= $key;
		
		return $new_key;
	}
	
}
