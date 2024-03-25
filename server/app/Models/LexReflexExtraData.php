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
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexExtraData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexExtraData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexExtraData query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexExtraData whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexExtraData whereLocales(string $column, array $locales)
 * @mixin \Eloquent
 */
class LexReflexExtraData extends Model
{
    use CrudTrait;
    use HasFactory;
    use HasTranslations;

    protected $guarded = ['id','created_at','updated_at'];
    protected $translatable = ['value'];
}
