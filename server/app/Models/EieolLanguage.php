<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EieolLanguage
 *
 * @property int $id
 * @property string|null $language
 * @property string|null $custom_keyboard_layout
 * @property string|null $substitutions
 * @property string|null $custom_sort
 * @property string|null $lang_attribute
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage query()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereCustomKeyboardLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereCustomSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereLangAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereSubstitutions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLanguage whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolLanguage extends Model {

    use CrudTrait;

	protected $table = 'eieol_language';

    protected $guarded = ['id'];
}
