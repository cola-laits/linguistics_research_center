<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\EieolLanguage
 *
 * @property int $id
 * @property string|null $language
 * @property string|null $custom_keyboard_layout
 * @property string|null $substitutions
 * @property string|null $custom_sort
 * @property string|null $lang_attribute
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @method static Builder|EieolLanguage newModelQuery()
 * @method static Builder|EieolLanguage newQuery()
 * @method static Builder|EieolLanguage query()
 * @method static Builder|EieolLanguage whereCreatedAt($value)
 * @method static Builder|EieolLanguage whereCreatedBy($value)
 * @method static Builder|EieolLanguage whereCustomKeyboardLayout($value)
 * @method static Builder|EieolLanguage whereCustomSort($value)
 * @method static Builder|EieolLanguage whereId($value)
 * @method static Builder|EieolLanguage whereLangAttribute($value)
 * @method static Builder|EieolLanguage whereLanguage($value)
 * @method static Builder|EieolLanguage whereSubstitutions($value)
 * @method static Builder|EieolLanguage whereUpdatedAt($value)
 * @method static Builder|EieolLanguage whereUpdatedBy($value)
 * @mixin Eloquent
 */
class EieolLanguage extends Model {

    use CrudTrait;

	protected $table = 'eieol_language';

    protected $guarded = ['id'];
}
