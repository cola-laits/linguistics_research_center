<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\EieolHeadWord;
use App\Models\EieolGloss;
use Illuminate\Support\Carbon;

class EieolElement extends Model {
	protected $table = 'eieol_element';

	public function head_word()
	{
		return $this->belongsTo(EieolHeadWord::class);
	}

	public function gloss()
	{
		return $this->belongsTo(EieolGloss::class);
	}
}
