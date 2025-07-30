<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EieolLanguage extends Model {

    use CrudTrait;

	protected $table = 'eieol_language';

    protected $guarded = ['id'];
}
