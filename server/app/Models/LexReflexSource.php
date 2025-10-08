<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\LexReflex;
use App\Models\LexSource;
use Illuminate\Support\Carbon;

class LexReflexSource extends Model {
	protected $table = 'lex_reflex_source';
    protected $guarded = ['id', 'created_at', 'updated_at'];

	public function reflex()
	{
		return $this->hasOne(LexReflex::class, 'id', 'reflex_id');
	}

	public function source()
	{
		return $this->hasOne(LexSource::class, 'id', 'source_id');
	}
}
