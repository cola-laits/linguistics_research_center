<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LexEtymaExtraData extends Model
{
    use HasTranslations;

    protected $guarded = ['id','created_at','updated_at'];
    protected $translatable = ['value'];
}
