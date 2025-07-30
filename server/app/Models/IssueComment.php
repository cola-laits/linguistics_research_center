<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Issue;
use Illuminate\Support\Carbon;

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
