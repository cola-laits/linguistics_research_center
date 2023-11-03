<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|IsoLanguage newModelQuery()
 * @method static Builder|IsoLanguage newQuery()
 * @method static Builder|IsoLanguage query()
 * @method static Builder|IsoLanguage whereComment($value)
 * @method static Builder|IsoLanguage whereIsoId($value)
 * @method static Builder|IsoLanguage whereLanguageType($value)
 * @method static Builder|IsoLanguage wherePart1($value)
 * @method static Builder|IsoLanguage wherePart2B($value)
 * @method static Builder|IsoLanguage wherePart2T($value)
 * @method static Builder|IsoLanguage whereRefName($value)
 * @method static Builder|IsoLanguage whereScope($value)
 * @mixin Eloquent
 */
class IsoLanguage extends Model {
	protected $table = 'iso_language';
}
