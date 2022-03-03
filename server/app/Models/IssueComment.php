<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Issue;

/**
 * App\Models\IssueComment
 *
 * @property int $id
 * @property int $issue_id
 * @property string $type
 * @property string $text
 * @property string $user_logon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Issue $issue
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment whereIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IssueComment whereUserLogon($value)
 * @mixin \Eloquent
 */
class IssueComment extends Model
{
    protected $table = 'issue_comment';

    protected function serializeDate(DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    public function issue() {
        return $this->belongsTo(Issue::class);
    }
}
