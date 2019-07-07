<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueComment extends Model
{
    protected $table = 'issue_comment';

    public function issue() {
        return $this->belongsTo('App\Issue');
    }
}
