<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Issue;
use Illuminate\Support\Carbon;

/**
 * App\Models\IssueComment
 *
 * @property int $id
 * @property int $issue_id
 * @property string $type
 * @property string $text
 * @property string $user_logon
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Issue $issue
 * @method static Builder|IssueComment newModelQuery()
 * @method static Builder|IssueComment newQuery()
 * @method static Builder|IssueComment query()
 * @method static Builder|IssueComment whereCreatedAt($value)
 * @method static Builder|IssueComment whereId($value)
 * @method static Builder|IssueComment whereIssueId($value)
 * @method static Builder|IssueComment whereText($value)
 * @method static Builder|IssueComment whereType($value)
 * @method static Builder|IssueComment whereUpdatedAt($value)
 * @method static Builder|IssueComment whereUserLogon($value)
 * @mixin Eloquent
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
