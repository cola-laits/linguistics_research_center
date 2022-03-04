<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\IsoLanguage
 *
 * @property string|null $iso_id
 * @property string|null $Part2B
 * @property string|null $Part2T
 * @property string|null $Part1
 * @property string|null $Scope
 * @property string|null $Language_Type
 * @property string|null $Ref_Name
 * @property string|null $Comment
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage query()
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage whereIsoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage whereLanguageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage wherePart1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage wherePart2B($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage wherePart2T($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage whereRefName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IsoLanguage whereScope($value)
 * @mixin \Eloquent
 */
class IsoLanguage extends Model {
	protected $table = 'iso_language';
}
