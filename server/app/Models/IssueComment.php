<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class IssueComment extends Model
{
    protected $table = 'issue_comment';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }
}
