<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LexEtymaCrossReference
 *
 * @property int $id
 * @property int $from_etyma_id
 * @property int $to_etyma_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaCrossReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaCrossReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaCrossReference query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaCrossReference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaCrossReference whereFromEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaCrossReference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaCrossReference whereToEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaCrossReference whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LexEtymaCrossReference extends Model {
	protected $table = 'lex_etyma_cross_reference';
}
