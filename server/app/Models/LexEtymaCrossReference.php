<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LexEtymaCrossReference
 *
 * @property int $id
 * @property int $from_etyma_id
 * @property int $to_etyma_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaCrossReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaCrossReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaCrossReference query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaCrossReference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaCrossReference whereFromEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaCrossReference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaCrossReference whereToEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaCrossReference whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LexEtymaCrossReference extends Model {
	protected $table = 'lex_etyma_cross_reference';
}
