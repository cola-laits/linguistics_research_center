<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\IsoLanguage
 *
 * @property int|null $id
 * @property string|null $Part2B
 * @property string|null $Part2T
 * @property string|null $Part1
 * @property string|null $Scope
 * @property string|null $Language_Type
 * @property string|null $Ref_Name
 * @property string|null $Comment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage whereLanguageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage wherePart1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage wherePart2B($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage wherePart2T($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage whereRefName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage whereScope($value)
 * @mixin \Eloquent
 * @property string|null $iso_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IsoLanguage whereIsoId($value)
 */
class IsoLanguage extends Model {
	protected $table = 'iso_language';
}
