<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Page extends Model {

    use CrudTrait;
    use HasTranslations;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'page';
    protected $guarded = ['id'];
    protected $translatable = ['name', 'content'];

}
