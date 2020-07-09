<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * App\IssueComment
 *
 * @property int $id
 * @property int $issue_id
 * @property string $type
 * @property string $text
 * @property string $user_logon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Issue $issue
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment whereIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IssueComment whereUserLogon($value)
 * @mixin \Eloquent
 */
class IssueComment extends Model
{
    protected $table = 'issue_comment';

    protected function serializeDate(DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    public function issue() {
        return $this->belongsTo('App\Issue');
    }
}
