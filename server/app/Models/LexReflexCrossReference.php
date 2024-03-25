<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * 
 *
 * @property-read \App\Models\LexReflex|null $from_reflex
 * @property-read \App\Models\LexReflex|null $to_reflex
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexCrossReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexCrossReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexCrossReference query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexCrossReference whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexCrossReference whereLocales(string $column, array $locales)
 * @mixin \Eloquent
 */
class LexReflexCrossReference extends Pivot
{
    use CrudTrait;
    use HasFactory;
    use HasTranslations;

    protected $table = 'lex_reflex_cross_reference';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $translatable = ['relationship'];
    public $incrementing = true;

    public function to_reflex() : BelongsTo
    {
        return $this->belongsTo(LexReflex::class, 'to_reflex_id');
    }

    public function from_reflex() : BelongsTo
    {
        return $this->belongsTo(LexReflex::class, 'from_reflex_id');
    }
}
