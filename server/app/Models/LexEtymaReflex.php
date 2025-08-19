<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

class LexEtymaReflex extends Model {

	protected $table = 'lex_etyma_reflex';

    protected $guarded = [
        'id','created_at','updated_at'
    ];

	public function etyma()
	{
		return $this->hasOne(LexEtyma::class, 'id', 'etyma_id');
	}

	public function reflex()
	{
		return $this->hasOne(LexReflex::class, 'id', 'reflex_id');
	}
}
