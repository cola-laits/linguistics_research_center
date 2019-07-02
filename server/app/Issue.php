<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $table = 'issue';

    protected $guarded = ['id','created_at','updated_at'];

    public function comments() {
        return $this->hasMany('App\IssueComment');
    }
}
