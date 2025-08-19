<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model {

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
