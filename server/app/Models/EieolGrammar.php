<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\EieolLesson;
use Illuminate\Support\Carbon;

class EieolGrammar extends Model {
	protected $table = 'eieol_grammar';

	public function lesson()
	{
		return $this->belongsTo(EieolLesson::class);
	}
}
