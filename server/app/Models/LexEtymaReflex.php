<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\LexEtyma;
use App\Models\LexReflex;
use Illuminate\Support\Carbon;

class LexEtymaReflex extends Model {
    use CrudTrait;
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
