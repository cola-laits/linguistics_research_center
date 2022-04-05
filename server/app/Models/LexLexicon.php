<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class LexLexicon extends Model
{
    use CrudTrait;

    protected $table = 'lex_lexicon';

    protected $guarded = ['id'];

}
