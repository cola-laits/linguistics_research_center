<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolLanguage
 *
 * @property int $id
 * @property string|null $language
 * @property string|null $custom_keyboard_layout
 * @property string|null $substitutions
 * @property string|null $custom_sort
 * @property string|null $lang_attribute
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereCustomKeyboardLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereCustomSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereLangAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereSubstitutions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLanguage whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolLanguage extends Model {

    use CrudTrait;

	protected $table = 'eieol_language';
}
