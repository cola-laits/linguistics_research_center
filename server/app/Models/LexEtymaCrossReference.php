<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\LexEtymaCrossReference
 *
 * @property int $id
 * @property int $from_etyma_id
 * @property int $to_etyma_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|LexEtymaCrossReference newModelQuery()
 * @method static Builder|LexEtymaCrossReference newQuery()
 * @method static Builder|LexEtymaCrossReference query()
 * @method static Builder|LexEtymaCrossReference whereCreatedAt($value)
 * @method static Builder|LexEtymaCrossReference whereFromEtymaId($value)
 * @method static Builder|LexEtymaCrossReference whereId($value)
 * @method static Builder|LexEtymaCrossReference whereToEtymaId($value)
 * @method static Builder|LexEtymaCrossReference whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LexEtymaCrossReference extends Model {
	protected $table = 'lex_etyma_cross_reference';
}
