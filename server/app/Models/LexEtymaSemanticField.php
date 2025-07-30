<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\LexEtyma;
use App\Models\LexSemanticField;
use Illuminate\Support\Carbon;

class LexEtymaSemanticField extends Model {
	protected $table = 'lex_etyma_semantic_field';

    protected $guarded = ['id'];

	public function etyma()
	{
		return $this->hasOne(LexEtyma::class, 'id', 'etyma_id');
	}

	public function semantic_field()
	{
		return $this->hasOne(LexSemanticField::class, 'id', 'semantic_field_id');
	}
}
