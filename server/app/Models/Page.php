<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Page
 *
 * @property int $id
 * @property string|null $slug
 * @property string|null $name
 * @property string|null $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Page newModelQuery()
 * @method static Builder|Page newQuery()
 * @method static Builder|Page query()
 * @method static Builder|Page whereContent($value)
 * @method static Builder|Page whereCreatedAt($value)
 * @method static Builder|Page whereId($value)
 * @method static Builder|Page whereName($value)
 * @method static Builder|Page whereSlug($value)
 * @method static Builder|Page whereUpdatedAt($value)
 * @property-read mixed $translations
 * @method static Builder|Page whereLocale(string $column, string $locale)
 * @method static Builder|Page whereLocales(string $column, array $locales)
 * @mixin Eloquent
 */
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
