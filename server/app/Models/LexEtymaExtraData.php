<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaExtraData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaExtraData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaExtraData query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaExtraData whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaExtraData whereLocales(string $column, array $locales)
 * @mixin \Eloquent
 */
class LexEtymaExtraData extends Model
{
    use CrudTrait;
    use HasFactory;
    use HasTranslations;

    protected $guarded = ['id','created_at','updated_at'];
    protected $translatable = ['value'];
}
